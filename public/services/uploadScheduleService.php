<?php

require_once __DIR__ . '/../boot.php';
require_once __DIR__ . '/../DTO/ScheduleDTO.php';

use IcsReader\IcsReader;
use Illuminate\Support\Arr;

$target_dir = "../schedule/";
$target_file = $target_dir . basename($_FILES["file"]["name"][0]);
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$universityId = Arr::get($_POST, 'university');

if (!$_FILES["file"]["name"][0]) {
    flash('Выберите файл');
    header('Location: /uploadSchedule.php');
    die;
}

if (!$universityId) {
    flash('Выберите ВУЗ');
    header('Location: /uploadSchedule.php');
    die;
}

if ($fileType !== "ics") {
    flash('Выберите файл с форматом .ics');
    header('Location: /uploadSchedule.php');
    die;
}

if (move_uploaded_file($_FILES["file"]["tmp_name"][0], $target_file)) {
    $file = file_get_contents($target_file);

    $reader = new IcsReader();
    $ics = $reader->parse($file);

    $scheduleDTOs = [];
    foreach ($ics->getEvents() as $event) {
        $class = new ScheduleDTO($event);

        $group = getGroupByNameBy($class->getGroupName());
        if (!$group) {
            $group = createGroup($class->getGroupName());
        }

        if (getClassByUID($class->getUid())) {
            continue;
        }

        createClass($class->toArray(
            groupId: $group['id'],
            teacherId: $_SESSION['user']['id'],
            universityId: $universityId,
        ));
    }

    flash("Файл " . basename( $_FILES["file"]["name"][0]) . " был импортирован");
} else {
    flash('Извините, произошла ошибка при импорте вашего файла');
}

header('Location: /uploadSchedule.php');
die;

function getGroupByNameBy(string $name): array|bool
{
    $stmt = pdo()->prepare("SELECT * FROM class_groups WHERE group_name = :group_name;");
    $stmt->execute(['group_name' => $name]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createGroup(string $name): array|bool
{
    $stmt = pdo()->prepare("INSERT INTO class_groups (group_name) VALUES (:group_name)");
    $stmt->execute(['group_name' => $name]);

    return getGroupByNameBy($name);
}

function getClassByUID(string $uid): array|bool
{
    $stmt = pdo()->prepare("SELECT * FROM classes WHERE uid = :uid;");
    $stmt->execute(['uid' => $uid]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createClass(array $dto): array|bool
{
    $stmt = pdo()->prepare("INSERT INTO classes (class_start, class_end, place, university_id, group_id, teacher_id, subject, uid) VALUES (:class_start, :class_end, :place, :university_id, :group_id, :teacher_id, :subject, :uid)");
    $stmt->execute([
        'class_start' => $dto['class_start'],
        'class_end' => $dto['class_end'],
        'place' => $dto['place'],
        'university_id' => $dto['university_id'],
        'group_id' => $dto['group_id'],
        'teacher_id' => $dto['teacher_id'],
        'subject' => $dto['subject'],
        'uid' => $dto['uid'],
    ]);

    return getClassByUID($dto['uid']);
}
