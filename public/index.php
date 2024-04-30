<?php
    require_once __DIR__ . '/boot.php';
?>

<?php if (check_auth()) { ?>
    <nav>
        <ul>
            <li><a href="uploadSchedule.php">Импорт расписания</a></li>
            <li><a href="#">Импорт студентов</a></li>
        </ul>
    </nav>

    <h1>Добро пожаловать, <?=htmlspecialchars($_SESSION['user']['name'])?>!</h1>
    <span>Вы зарегистрированы как <?=$_SESSION['user']['role'] === 'student' ? 'Студент' : 'Преподаватель'?></span>

    <form class="mt-5" method="post" action="logout.php">
        <button type="submit" class="btn btn-primary">Выйти</button>
    </form>

<?php }?>
