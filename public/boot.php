<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/repository.php';

use Carbon\Carbon;
use Illuminate\Support\Arr;
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

function flash(?string $message = null): void
{
    if ($message) {
        $_SESSION['flash'] = $message;
    } else {
        if (!empty($_SESSION['flash'])) { ?>
            <div class="alert alert-danger mb-3">
                <?=$_SESSION['flash']?>
            </div>
        <?php }
        unset($_SESSION['flash']);
    }
}

function check_auth(): bool
{
    return !!($_SESSION['user']['id'] ?? false);
}

function check_is_student(): bool
{
    return Arr::get($_SESSION, 'user.role') === 'student';
}

function check_is_teacher(): bool
{
    return $_SESSION['user']['role'] === 'teacher';
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

function generateUniqueHash(): string
{
    return substr(uniqid(), -6);
}

function checkTimeRange(string $startTime, string $endTime): bool
{
    $now = Carbon::now()->addHours(3);
    $start = Carbon::parse($startTime)->format('Y-m-d H:i:s.u');
    $end = Carbon::parse($endTime)->format('Y-m-d H:i:s.u');

    return $now->between($start, $end);
}

function parseStatus(int|float $status): string
{
    return match ($status) {
        0.0 => 'Не был',
        0.5 => 'Опоздал',
        1.0 => 'Был',
        default => 'Ошибка'
    };
}
