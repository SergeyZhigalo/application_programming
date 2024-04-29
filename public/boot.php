<?php

use JetBrains\PhpStorm\NoReturn;
use Dotenv\Dotenv;

session_start();

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

function pdo(): PDO
{
    static $pdo;

    if (!$pdo) {
        $dsn = "pgsql:host=".$_ENV['DB_HOST'].";port=".$_ENV['DB_POST'].";dbname=".$_ENV['DB_NAME'];
        $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $pdo;
}

#[NoReturn] function dd(...$args): void
{
    echo '<pre>';
    foreach ($args as $arg) {
        var_dump($arg);
    }
    echo '</pre>';
    die;
}
