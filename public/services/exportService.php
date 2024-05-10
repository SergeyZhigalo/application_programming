<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;

require_once __DIR__ . '/../boot.php';

$class = getClassById($_POST['class']);
$classes = getClassesByUniversityIdGroupIdTeacherIdSubject(
    universityId: $_POST['university'],
    groupId: $_POST['group'],
    teacherId: $_SESSION['user']['id'],
    subject: $class['subject'],
);

$classesCollection = new Collection($classes);
$dateStart = Carbon::create(1);
$dateEnd = Carbon::create(3000);

if ($_POST['dateStart']) {
    $dateStart = Carbon::parse($_POST['dateStart']);
}
if ($_POST['dateEnd']) {
    $dateEnd = Carbon::parse($_POST['dateEnd']);
}

$classesCollection = $classesCollection->whereBetween('class_start', [$dateStart->startOfDay(), $dateEnd->endOfDay()]);

$file = '../export/' . Carbon::now()->getTimestamp() . '.csv';
$csv = fopen($file, "w");

fputcsv($csv, ['id', 'full_name', 'subject', 'status', 'email', 'uid']);

if (!empty($classesCollection->pluck('id')->all())) {
    $exportData = getClassAttendanceByClassIdsWithFilterDate($classesCollection->pluck('id')->all());
    foreach ($exportData as $item) {
        fputcsv($csv, [
            $item['id'],
            $item['full_name'],
            $item['subject'],
            ceil($item['status']),
            $item['email'],
            $item['uid'],
        ]);
    }
}

fclose($csv);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
readfile($file);

header('Location: /export.php');
die;

function getClassAttendanceByClassIdsWithFilterDate(array $classIds): array|false
{
    $classIds = implode(',', $classIds);
    $stmt = pdo()->prepare("
        SELECT class_attendance.*, classes.subject, classes.uid, students.full_name, students.email
        FROM class_attendance
        JOIN classes ON class_attendance.class_id = classes.id
        JOIN students ON class_attendance.student_id = students.id
        WHERE class_id IN ($classIds);
    ");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
