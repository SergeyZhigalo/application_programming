<?php

require_once __DIR__ . '/../boot.php';

$stmt = pdo()->prepare("SELECT * FROM teachers WHERE full_name = :full_name OR email = :email");
$stmt->execute([
    'full_name' => $_POST['full_name'],
    'email' => $_POST['email'],
]);
if ($stmt->rowCount() > 0) {
    flash('Это ФИО или email уже занято.');
    header('Location: /registerTeacher.php');
    die;
}

$stmt = pdo()->prepare("INSERT INTO teachers (full_name, email, password) VALUES (:full_name, :email, :password)");

$stmt->execute([
    'full_name' => $_POST['full_name'],
    'email' => $_POST['email'],
    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
]);

header('Location: /loginTeacher.php');
