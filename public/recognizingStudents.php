<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;

require_once __DIR__ . '/boot.php';

$class = getClassById($_GET['id']);
$students = getStudentsByGroupId($class['group_id']);
$classes = getClassesByUniversityIdGroupIdTeacherId(
    universityId: $class['university_id'],
    groupId: $class['group_id'],
    teacherId: $_SESSION['user']['id'],
);
$classesCollection = new Collection($classes);
$classAttendance = new Collection(getClassAttendanceByClassIds($classesCollection->pluck('id')->all()));
?>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
        border: 2px solid #333;
        font-family: Arial, sans-serif;
    }
    th, td {
        border: 2px solid #333;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    tr:nth-child(odd) {
        background-color: #ffffff;
    }
    tr:hover {
        background-color: #ddd;
    }
    a.button {
        display: inline-block;
        padding: 5px 10px;
        text-decoration: none;
        background-color: #4CAF50;
        color: white;
        border: 1px solid #4CAF50;
        border-radius: 5px;
        transition: background-color 0.3s;
    }
    a.button:hover {
        background-color: #45a049;
    }
</style>

<h1 class="mb-5"><?=$class['subject']?></h1>
<h2 class="mb-5"><?=$class['university_name']?></h2>
<h2 class="mb-5"><?=$class['group_name']?></h2>

<form action="services/recognizingStudentsService.php" method="post" enctype="multipart/form-data">

<table>
    <thead>
    <tr>
        <th>ФИО</th>
        <?php foreach($classes as $class) { ?>
            <th style="width: 250px !important;"><?=Carbon::parse($class['class_start'])->format('d.m.Y H:i:s')?></th>
        <?php }?>
    </tr>
    </thead>
    <tbody>
    <?php foreach($students as $student) { ?>
        <tr>
            <td><?=$student['full_name']?></td>
            <?php foreach($classes as $class) { ?>
                <?php $status = $classAttendance->where('class_id', $class['id'])->where('student_id', $student['id'])->first(); ?>
                <td style="width: 250px !important;">
                    <select name="status[<?=$student['id']?>][<?=$class['id']?>]" id="status[<?=$student['id']?>][<?=$class['id']?>]">
                        <option value="null">Не выбрано</option>
                        <option value="0" <?= $status && (int)$status['status'] === 0 ? 'selected' : ''?>>Отсутствует</option>
                        <option value="0.5" <?= $status && (float)$status['status'] === 0.5 ? 'selected' : ''?>>Опоздал</option>
                        <option value="1" <?= $status && (int)$status['status'] === 1 ? 'selected' : ''?>>Был</option>
                    </select>
                </td>
            <?php }?>
        </tr>
    <?php }?>
    </tbody>
</table>
<input type="submit" value="Сохранить">

</form>
