<?php

declare(strict_types=1);

namespace App;

use App\BankAccount;
use App\Transaction;

class TransactionController
{
    public function __construct(private BankAccount $account, private CurrencyService $currencyService)
    {
    }

    public function index(): void
    {
    $filter = $_GET['filter'] ?? 'all';
    $transactions = $this->account->getTransactions($filter);
    $balance = $this->account->getBalance();
    $income = $this->account->getIncome();
    $expenses = $this->account->getExpenses();
    try {
        $actualRate = $this->currencyService->getRate('EUR');
    } catch (\Exception $e) {
        $actualRate = 1.0;
    }
    $balanceEuro = $balance * $actualRate;
    require_once __DIR__.'/../views/index.view.php';
    }

    public function store(): void
    {
    $type = $_POST['type'] ?? 'expense';
    $amount = (float) ($_POST['amount'] ?? 0);
    $category = $_POST['category'] ?? 'Другое';
    $comment = $_POST['comment'] ?? '';

    if ($amount > 0) {
        $transaction = Transaction::create($type, $amount, $category, $comment);
        $this->account->addTransaction($transaction);
    }
    header('Location: /');
    exit;
    }

    public function delete(): void
    {
        $id = $_POST['delete_id'] ?? '';
        if ($id) {
            $this->account->deleteTransaction($id);
        }

        header('Location: /');
        exit;
    }

    public function clear(): void
    {
        $this->account->deleteAllTransactions();

        header('Location: /');
        exit;
    }
}