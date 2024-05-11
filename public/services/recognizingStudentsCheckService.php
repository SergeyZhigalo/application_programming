<?php

use Illuminate\Support\Collection;

require_once __DIR__ . '/../boot.php';

$class = getClassById($_POST['class']);
$check = getClassAttendanceStudentByGroupIdAndClassId($class['group_id'], $_POST['class']);

if (!checkTimeRange($class['class_start'], $check['time_end'])) {
    flash('Время для отметки закончилось');
    header('Location: /');
    die;
}

if (!$check) {
    flash('Возникла ошибка');
    header('Location: /');
    die;
}

if ($check['hash'] !== $_POST['code']) {
    flash('Код введен неверно');
    header('Location: /');
    die;
}

$classAttendanceCollection = new Collection(getClassAttendanceByClass($class['id']));
if ($classAttendanceCollection->count() >= $check['count']) {
    flash('Превышено количество мест');
    header('Location: /');
    die;
}

clearClassAttendanceByStudent($class['id'], $_SESSION['user']['id']);
setClassAttendanceByStudent($class['id'], $_SESSION['user']['id']);
flash('Вы отмечены');
header('Location: /');
die;

function getClassAttendanceByClass(string $classId): array|false
{
    $stmt = pdo()->prepare("SELECT * FROM class_attendance WHERE class_id = :class_id AND status > 0;");
    $stmt->execute(['class_id' => $classId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getClassAttendanceStudentByGroupIdAndClassId(string $groupId, string $classId): array|false
{
    $stmt = pdo()->prepare("SELECT * FROM class_attendance_students WHERE group_id = :group_id AND class_id = :class_id;");
    $stmt->execute([
        'group_id' => $groupId,
        'class_id' => $classId,
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function clearClassAttendanceByStudent(string $classId, string $studentId): void
{
    $stmt = pdo()->prepare("DELETE FROM class_attendance WHERE student_id = :student_id AND class_id = :class_id");
    $stmt->execute([
        'class_id' => $classId,
        'student_id' => $studentId,
    ]);
}

function setClassAttendanceByStudent(string $classId, string $studentId): void
{
    $stmt = pdo()->prepare("INSERT INTO class_attendance (class_id, student_id, status) VALUES (:class_id, :student_id, :status)");
    $stmt->execute([
        'class_id' => $classId,
        'student_id' => $studentId,
        'status' => '1.0'
    ]);
}
