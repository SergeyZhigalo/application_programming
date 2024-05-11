<?php

use Carbon\Carbon;
use Illuminate\Support\Arr;

require_once __DIR__ . '/boot.php';

$recognizingStudents = null;
$recognizingGroupHead = null;
if (check_is_student()) {
    $recognizingGroupHead = getRecognizingStudentsByGroupHeadForStudent($_SESSION['user']['id']);
    $recognizingStudents = getRecognizingStudentsByStudentsForStudent($_SESSION['user']['group_id']);
    $recognizingGroupHeadClass = getClassById($recognizingGroupHead['class_id']);
    $recognizingStudentsClass = getClassById($recognizingStudents['class_id']);
}
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
</style>

<?php if (check_auth()) { ?>
    <?php if (check_is_teacher()) { ?>
        <nav>
            <ul>
                <li><a href="uploadSchedule.php">Импорт расписания</a></li>
                <li><a href="uploadStudents.php">Импорт студентов</a></li>
                <li><a href="classes.php">Предметы</a></li>
                <li><a href="export.php">Экспорт</a></li>
            </ul>
        </nav>
    <?php }?>

    <h1>Добро пожаловать, <?=htmlspecialchars($_SESSION['user']['full_name'])?>!</h1>
    <?php if (Arr::get($_SESSION, 'user.group_head')) { ?>
        <h2>Вы староста</h2>
    <?php }?>

    <span>Вы зарегистрированы как <?=check_is_student() ? 'Студент' : 'Преподаватель'?></span>

    <?php if ($recognizingGroupHead && checkTimeRange($recognizingGroupHeadClass['class_start'], $recognizingGroupHeadClass['class_end'])) { ?>
        <h2>Проверка старостой</h2>
        <div>
            <a class="button" href="recognizingStudents.php?id=<?=$recognizingGroupHead['class_id']?>&isGroupHead=1">Отметить</a>
        </div>
    <?php }?>
    <?php if ($recognizingStudents && checkTimeRange($recognizingStudentsClass['class_start'], $recognizingStudents['time_end'])) { ?>
        <div>
            <h2>Самопроверка</h2>
            <?php flash(); ?>
            <form action="services/recognizingStudentsCheckService.php" method="post" enctype="multipart/form-data">
                <label for="code">Введите код</label>
                <input type="text" name="code" id="code" required>
                <input type="text" value="<?=$recognizingStudents['class_id']?>" name="class" id="class" style="display: none">

                <input type="submit" value="Отметиться">
            </form>
        </div>
    <?php }?>

    <?php if (check_is_student()) { ?>
        <div>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Предмет</th>
                    <th>Посещение</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach(getClassAttendanceForStudent($_SESSION['user']['id']) as $class) { ?>
                    <tr>
                        <td><?=$class['id']?></td>
                        <td><?=$class['subject']?></td>
                        <td><?=parseStatus($class['status'])?></td>
                        <td><?=Carbon::parse($class['class_start'])->format('Y-m-d')?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    <?php }?>

    <form class="mt-5" method="post" action="logout.php">
        <button type="submit" class="btn btn-primary">Выйти</button>
    </form>

<?php } else {?>
    <a href="loginTeacher.php">Войти как преподаватель</a>
    <a href="loginStudent.php">Войти как студент</a>
<?php }?>
