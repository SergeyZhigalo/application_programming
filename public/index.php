<?php
    require_once __DIR__ . '/boot.php';
?>

<?php if (check_auth()) { ?>
    <?php if (check_is_teacher()) { ?>
        <nav>
            <ul>
                <li><a href="uploadSchedule.php">Импорт расписания</a></li>
                <li><a href="uploadStudents.php">Импорт студентов</a></li>
            </ul>
        </nav>
    <?php }?>

    <h1>Добро пожаловать, <?=htmlspecialchars($_SESSION['user']['full_name'])?>!</h1>
    <span>Вы зарегистрированы как <?=check_is_student() ? 'Студент' : 'Преподаватель'?></span>

    <form class="mt-5" method="post" action="logout.php">
        <button type="submit" class="btn btn-primary">Выйти</button>
    </form>

<?php } else {?>
    <a href="loginTeacher.php">Войти как преподаватель</a>
    <a href="loginStudent.php">Войти как студент</a>
<?php }?>
