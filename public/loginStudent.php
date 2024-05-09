<?php
require_once __DIR__ . '/boot.php';

if (check_auth()) {
    header('Location: /');
    die;
}
?>

<h1 class="mb-5">Авторизация студента</h1>

<?php flash() ?>

<form method="post" action="services/loginStudentService.php">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Пароль</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Авторизоваться</button>
    </div>
</form>
