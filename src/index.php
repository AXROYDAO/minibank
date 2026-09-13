<?php

declare(strict_types=1);

// Подключаем один единственный файл автозагрузчика из корня:
require_once __DIR__.'/../vendor/autoload.php';

use App\BankAccount;
use App\Transaction;
use App\TransactionRepository;
use App\Database;
use App\TransactionController;
use App\CurrencyService;

session_start();

$pdo = Database::getConnection();

$currencyService = new CurrencyService();

$repository = new TransactionRepository($pdo);

$account = new BankAccount($repository);

$controller = new TransactionController($account, $currencyService);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die('403 Forbidden: Неверный CSRF-токен');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $uri === '/') {
    $controller->index();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $uri === '/transactions') {
    $controller->store();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $uri === '/transactions/delete') {
    $controller->delete();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $uri === '/transactions/clear') {
    $controller->clear();
} else {
    http_response_code(404);
    echo '404 Not Found';
}

