<?php

use Carbon\Carbon;

require_once __DIR__ . '/../boot.php';

clearGroupHeadByClassId($_POST['class']);
createClassAttendanceGroupHead(
    groupId: $_POST['group'],
    classId: $_POST['class'],
    count: $_POST['numbersOfStudents'],
    timeEnd: $_POST['time'],
);

header("Location: /recognizingStudents.php?id={$_POST['class']}");

function clearGroupHeadByClassId(string $classId): void
{
    $stmt = pdo()->prepare("DELETE FROM class_attendance_students WHERE class_id = :class_id");
    $stmt->execute(['class_id' => $classId]);
}

function createClassAttendanceGroupHead(string $groupId, string $classId, string $count, int $timeEnd): void
{
    $stmt = pdo()->prepare("INSERT INTO class_attendance_students (group_id, class_id, count, time_end, hash) VALUES (:group_id, :class_id, :count, :time_end, :hash)");
    $stmt->execute([
        'group_id' => $groupId,
        'class_id' => $classId,
        'count' => $count,
        'time_end' => Carbon::now()->addMinutes($timeEnd)->format('Y-m-d H:i:s.u'),
        'hash' => generateUniqueHash(),
    ]);
}
