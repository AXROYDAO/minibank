<?php

declare(strict_types=1);

// Подключаем один единственный файл автозагрузчика из корня:
require_once __DIR__.'/../vendor/autoload.php';

// Импортируем наш класс через use:
use App\BankAccount;

session_start();
if (! isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
}

$account = new BankAccount($_SESSION['transactions']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $delete_id = $_POST['delete_id'] ?? null;

    if ($action === 'clear_all') {
        $account->clearAllTransactions();
    }

    if ($action === 'add_transaction') {
        $type = $_POST['type'] ?? 'expense';
        $amount = (float) ($_POST['amount'] ?? 0);
        $category = $_POST['category'] ?? 'Другое';
        $comment = $_POST['comment'] ?? '';

        if ($amount > 0) {
            $account->addTransaction($type, $amount, $category, $comment);
        }
    }

    if ($delete_id !== null) {
        $account->deleteTransaction($delete_id);
    }

    $_SESSION['transactions'] = $account->getTransactions();
    header('Location: index.php');
    exit;
}

$filter = $_GET['filter'] ?? 'all';
$transactions = $account->getTransactions($filter);
$balance = $account->getBalance();
$income = $account->getIncome();
$expenses = $account->getExpenses();

require_once __DIR__.'/views/index.view.php';
