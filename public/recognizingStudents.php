<?php

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

require_once __DIR__ . '/boot.php';

$class = null;
if (Arr::get($_GET, 'id')) {
    $class = getClassById($_GET['id']);
}

if (!Arr::get($_GET, 'id') || !$class) {
    header("Location: /classes.php");
    die;
}

$students = getStudentsByGroupId($class['group_id']);
$classes = getClassesByUniversityIdGroupIdTeacherIdSubject(
    universityId: $class['university_id'],
    groupId: $class['group_id'],
    teacherId: $_SESSION['user']['id'],
    subject: $class['subject'],
);
$classesCollection = new Collection($classes);
if (check_is_teacher()) {
    $classAttendance = new Collection(getClassAttendanceByClassIds($classesCollection->pluck('id')->all()));
}
if (check_is_student()) {
    $classes = [$class];
    $classAttendance = new Collection(getClassAttendanceByClassIds([$_GET['id']]));
}
$studentsCollection = new Collection($students);
$isThereGroupHead = $studentsCollection->contains('group_head', true);
$isActiveClass = checkTimeRange($class['class_start'], $class['class_end']);
$recognizingStudentsByGroupHead = getRecognizingStudentsByGroupHeadByClassId($_GET['id']);
$recognizingStudentsByStudents = getRecognizingStudentsByStudentsByClassId($_GET['id']);
?>

<style>
    table {
        border-collapse: collapse;
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
    .recognizingStudentsByGroupHead, .recognizingStudentsByStudents {
        background-color: #f2f2f2;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        margin: 20px;
    }
    .bigText {
        font-size: 70px;
    }
</style>

<h1 class="mb-5"><?=$class['subject']?></h1>
<h2 class="mb-5"><?=$class['university_name']?></h2>
<h2 class="mb-5"><?=$class['group_name']?></h2>

<?php if (check_is_teacher()) { ?>
    <div class="recognizingStudentsByGroupHead">
        <h2><?= $recognizingStudentsByGroupHead ? 'Староста назначена' : 'Назначить старосту' ?></h2>
        <span>Примечание: назначить старосту отмечать группу можно только во время пары</span>

        <form action="services/recognizingStudentsByGroupHeadService.php" method="post" enctype="multipart/form-data">
            <input type="text" value="<?=$_GET['id']?>" name="class" id="class" style="display: none">
            <input type="text" value="<?=$class['group_id']?>" name="group" id="group" style="display: none">
            <label for="studentsGroupHead">Выберите старосту</label>
            <select name="studentsGroupHead" id="studentsGroupHead">
                <?php if (!$isThereGroupHead) { ?>
                    <option value="" selected>Выберите старосту</option>
                <?php }?>
                <?php foreach($students as $student) { ?>
                    <option value="<?=$student['id']?>" <?=$student['group_head'] ? 'selected' : ''?>><?=$student['full_name']?></option>
                <?php }?>
            </select>

            <input type="submit" value="Начать" <?=$isActiveClass ? '' : 'disabled'?>>
        </form>
    </div>

    <div class="recognizingStudentsByStudents">
        <h2><?= $recognizingStudentsByStudents ? 'Самопроверка начата' : 'Провести самопроверку' ?></h2>

        <form action="services/recognizingStudentsByStudentsService.php" method="post" enctype="multipart/form-data">
            <input type="text" value="<?=$_GET['id']?>" name="class" id="class" style="display: none">
            <input type="text" value="<?=$class['group_id']?>" name="group" id="group" style="display: none">
            <label for="numbersOfStudents">Введите количество студентов</label>
            <input type="number" name="numbersOfStudents" id="numbersOfStudents" min="1" value="<?=$studentsCollection->count()?>" required>
            <label for="time">Введите количество минут для самопроверки</label>
            <input type="number" name="time" id="time" min="1" required>

            <input type="submit" value="Начать" <?=$isActiveClass ? '' : 'disabled'?>>
        </form>

        <?=$recognizingStudentsByStudents ? "<div class='bigText'>{$recognizingStudentsByStudents['hash']}</div>" : ''?>

    </div>
<?php }?>

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
