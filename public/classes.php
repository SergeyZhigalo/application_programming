<?php

require_once __DIR__ . '/boot.php';

use Carbon\Carbon;

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

<h1 class="mb-5">Предметы</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Университет</th>
            <th>Аудитория</th>
            <th>Предмет</th>
            <th>Группа</th>
            <th>Время начала</th>
            <th>Время окончания</th>
            <th style="width: 93px"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach(getTeachersClasses($_SESSION['user']['id']) as $class) { ?>
            <tr>
                <td><?=$class['id']?></td>
                <td><?=$class['university_name']?></td>
                <td><?=$class['place']?></td>
                <td><?=$class['subject']?></td>
                <td><?=$class['group_name']?></td>
                <td><?=Carbon::parse($class['class_start'])->format('d.m.Y H:i:s')?></td>
                <td><?=Carbon::parse($class['class_end'])->format('d.m.Y H:i:s')?></td>
                <td style="width: 93px"><a class="button" href="recognizingStudents.php?id=<?=$class['id']?>">Отметить</a></td>
            </tr>
        <?php }?>
    </tbody>
</table>
