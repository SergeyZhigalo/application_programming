<?php

require_once __DIR__ . '/boot.php';

$stmt = pdo()->prepare("SELECT * FROM teachers WHERE name = :name OR email = :email");
$stmt->execute([
    'name' => $_POST['username'],
    'email' => $_POST['email'],
]);
if ($stmt->rowCount() > 0) {
    flash('Это имя пользователя или email уже занято.');
    header('Location: /');
    die;
}

$stmt = pdo()->prepare("INSERT INTO teachers (name, email, password) VALUES (:name, :email, :password)");

$stmt->execute([
    'name' => $_POST['username'],
    'email' => $_POST['email'],
    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
]);

header('Location: loginTeacher.php');
