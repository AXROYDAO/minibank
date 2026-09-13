<?php

declare(strict_types=1);

namespace App;

use App\BankAccount;
use App\Transaction;

// nit: final readonly 
// контролер какой то тослетый. можно было вынести бизнес логику в action классы или TransactionService
// в идеале метод контроллера должен быть в 1-5 строк
class TransactionController
{
    public function __construct(private BankAccount $account, private CurrencyService $currencyService)
    {
    }

    public function index(): void
    {
        // а че с табуляцией
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
        // вот так должно быть
        // и пробелов между кодом нету; тяжку читать
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