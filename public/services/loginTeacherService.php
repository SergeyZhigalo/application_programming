<?php

require_once __DIR__ . '/../boot.php';

$stmt = pdo()->prepare("SELECT * FROM teachers WHERE email = :email");
$stmt->execute(['email' => $_POST['email']]);
if (!$stmt->rowCount()) {
    flash('Пользователь с такими данными не зарегистрирован');
    header('Location: /loginTeacher.php');
    die;
}
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (password_verify($_POST['password'], $user['password'])) {
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = pdo()->prepare('UPDATE teachers SET password = :password WHERE email = :email');
        $stmt->execute([
            'email' => $_POST['email'],
            'password' => $newHash,
        ]);
    }
    $_SESSION['user'] = [
        'id' => $user['id'],
        'full_name' => $user['full_name'],
        'email' => $user['email'],
        'role' => 'teacher',
    ];
    header('Location: /');
    die;
}

flash('Пароль неверен');
header('Location: /loginTeacher.php');
