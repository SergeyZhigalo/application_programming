<?php

require_once __DIR__ . '/../boot.php';
require_once __DIR__ . '/../DTO/StudentDTO.php';

use Illuminate\Support\Arr;

$target_dir = "../students/";
$target_file = $target_dir . basename($_FILES["file"]["name"][0]);
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$groupId = Arr::get($_POST, 'group');

if (!$_FILES["file"]["name"][0]) {
    flash('Выберите файл');
    header('Location: /uploadStudents.php');
    die;
}

if (!$groupId) {
    flash('Выберите группу');
    header('Location: /uploadStudents.php');
    die;
}

if ($fileType !== "csv") {
    flash('Выберите файл с форматом .csv');
    header('Location: /uploadStudents.php');
    die;
}

if (move_uploaded_file($_FILES["file"]["tmp_name"][0], $target_file)) {
    $file = fopen($target_file, 'r');
    fgetcsv($file);

    while (($row = fgetcsv($file)) !== false) {
        $student = new StudentDTO([
            'fullName' => $row[0],
            'email' => $row[1],
        ]);

        if (getStudentByEmail($student->getFullName())) {
            continue;
        }

        createStudent($student->toArray($groupId));
//        dd($student->toArray($groupId));
    }
//dd($file);
//
//        createClass($class->toArray(
//            groupId: $group['id'],
//            teacherId: $_SESSION['user']['id'],
//            universityId: $universityId,
//        ));
//    }

    flash("Файл " . basename( $_FILES["file"]["name"][0]) . " был импортирован");
} else {
    flash('Извините, произошла ошибка при импорте вашего файла');
}

header('Location: /uploadStudents.php');
die;

function getStudentByEmail(string $email): array|bool
{
    $stmt = pdo()->prepare("SELECT * FROM students WHERE email = :email;");
    $stmt->execute(['email' => $email]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createStudent(array $dto): array|bool
{
    $stmt = pdo()->prepare("INSERT INTO students (full_name, email, group_id, group_head, password) VALUES (:full_name, :email, :group_id, :group_head, :password)");
    $stmt->execute([
        'full_name' => $dto['full_name'],
        'email' => $dto['email'],
        'group_id' => $dto['group_id'],
        'group_head' => $dto['group_head'],
        'password' => $dto['password'],
    ]);

    return getStudentByEmail($dto['email']);
}
