<?php

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

    <form class="mt-5" method="post" action="logout.php">
        <button type="submit" class="btn btn-primary">Выйти</button>
    </form>

<?php } else {?>
    <a href="loginTeacher.php">Войти как преподаватель</a>
    <a href="loginStudent.php">Войти как студент</a>
<?php }?>
