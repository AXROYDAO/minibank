<?php

declare(strict_types=1);

namespace App;

class BankAccount
{
    private $transactions = [];

    public function __construct(array $initial_transactions = [])
    {
        $this->transactions = $initial_transactions;
    }

    public function getIncome(): float
    {
        $income = 0.0;
        foreach ($this->transactions as $transaction) {
            if ($transaction['type'] === 'income') {
                $income += $transaction['amount'];
            }
        }

        return $income;
    }

    public function getExpenses(): float
    {
        $expenses = 0.0;
        foreach ($this->transactions as $transaction) {
            if ($transaction['type'] === 'expense') {
                $expenses += $transaction['amount'];
            }
        }

        return $expenses;
    }

    public function getBalance(): float
    {
        return $this->getIncome() - $this->getExpenses();
    }

    public function addTransaction(string $type, float $amount, string $category, string $comment): void
    {
        $transaction = [
            'id' => uniqid(),
            'type' => $type,
            'amount' => $amount,
            'category' => $category,
            'comment' => $comment,
            'date' => date('Y-m-d H:i'),
        ];
        $this->transactions[] = $transaction;
    }

    public function clearAllTransactions(): void
    {
        $this->transactions = [];
    }

    public function deleteTransaction(string $id): void
    {
        $this->transactions = array_filter($this->transactions, function ($t) use ($id) {
            return $t['id'] !== $id;
        });
    }

    public function getTransactions(string $filter = 'all'): array
    {
        if ($filter === 'income') {
            return array_filter($this->transactions, function ($t) {
                return $t['type'] === 'income';
            });
        }
        if ($filter === 'expense') {
            return array_filter($this->transactions, function ($t) {
                return $t['type'] === 'expense';
            });
        }

        return $this->transactions;
    }
}
