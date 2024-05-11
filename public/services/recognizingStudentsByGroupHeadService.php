<?php

use Illuminate\Support\Arr;

require_once __DIR__ . '/../boot.php';

if (!$_POST['studentsGroupHead']) {
    flash('Выберите студента');
    header("Location: /recognizingStudents.php?id={$_POST['class']}");
    die;
}

$student = getStudentById(Arr::get($_POST, 'studentsGroupHead', 0));

if (!$student) {
    flash('Выберите студента');
    header("Location: /recognizingStudents.php?id={$_POST['class']}");
    die;
}

clearGroupHeadByGroupId($_POST['group']);
setGroupHeadByStudentId($_POST['studentsGroupHead']);
clearClassAttendanceGroupHeadByClassId($_POST['class']);
createClassAttendanceGroupHead($_POST['studentsGroupHead'], $_POST['class']);

header("Location: /recognizingStudents.php?id={$_POST['class']}");

function clearGroupHeadByGroupId(string $groupId): void
{
    $stmt = pdo()->prepare("UPDATE students SET group_head = false WHERE group_id = :group_id;");
    $stmt->execute(['group_id' => $groupId]);
}

function setGroupHeadByStudentId(string $id): void
{
    $stmt = pdo()->prepare("UPDATE students SET group_head = true WHERE id = :id;");
    $stmt->execute(['id' => $id]);
}

function clearClassAttendanceGroupHeadByClassId(string $classId): void
{
    $stmt = pdo()->prepare("DELETE FROM class_attendance_group_head WHERE class_id = :class_id");
    $stmt->execute(['class_id' => $classId]);
}

function createClassAttendanceGroupHead(string $studentId, string $classId): void
{
    $stmt = pdo()->prepare("INSERT INTO class_attendance_group_head (student_id, class_id) VALUES (:student_id, :class_id)");
    $stmt->execute([
        'student_id' => $studentId,
        'class_id' => $classId,
    ]);
}
