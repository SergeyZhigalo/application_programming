<?php

use Illuminate\Support\Collection;

require_once __DIR__ . '/../boot.php';

$statusCollection = new Collection($_POST['status']);
clearClassAttendanceByClassIds(array_keys($statusCollection->first()));

foreach ($_POST['status'] as $studentId => $value) {
    foreach ($value as $classId => $status) {
        if ($status === 'null') {
            continue;
        }

        createClassAttendance($classId, $studentId, $status);
    }
}

header('Location: /classes.php');
die;

function clearClassAttendanceByClassIds(array $ids): void
{
    $classIds = implode(',', $ids);
    $stmt = pdo()->prepare("DELETE FROM class_attendance WHERE class_id IN ($classIds)");
    $stmt->execute();
}

function createClassAttendance(string $classId, string $studentId, string $status): void
{
    $stmt = pdo()->prepare("INSERT INTO class_attendance (class_id, student_id, status) VALUES (:class_id, :student_id, :status)");
    $stmt->execute([
        'class_id' => $classId,
        'student_id' => $studentId,
        'status' => $status,
    ]);
}
