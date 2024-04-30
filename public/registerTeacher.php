<?php
    require_once __DIR__ . '/boot.php';

    if (check_auth()) {
        header('Location: /');
        die;
    }
?>

<h1 class="mb-5">Регистрация преподавателя</h1>

<?php flash(); ?>

<form method="post" action="services/registerTeacherService.php">
    <div class="mb-3">
        <label for="name" class="form-label">ФИО</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Почта</label>
        <input type="text" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Пароль</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
</form>
