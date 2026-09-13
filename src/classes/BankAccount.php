<?php

declare(strict_types=1);

namespace App;

final readonly class BankAccount
{
    public function __construct(private TransactionRepository $repository)
    {
    }

    public function getIncome(): float
    {
        $income = 0.0;
        foreach ($this->getTransactions() as $transaction) {
            if ($transaction->isIncome()) {
                $income += $transaction->getAmount();
            }
        }

        return $income;
    }

    public function getExpenses(): float
    {
        $expenses = 0.0;
        foreach ($this->getTransactions() as $transaction) {
            if ($transaction->isExpense()) {
                $expenses += $transaction->getAmount();
            }
        }

        return $expenses;
    }

    public function getBalance(): float
    {
        return $this->getIncome() - $this->getExpenses();
    }

    public function addTransaction(Transaction $transaction): void
    {
        $this->repository->save($transaction);
    }

    public function getTransactions(string $filter = 'all'): array
    {
        $transactions = $this->repository->findAll();

        if ($filter === 'income') {
            return array_filter($transactions, fn(Transaction $t) => $t->isIncome());
        }

        if ($filter === 'expense') {
            return array_filter($transactions, fn(Transaction $t) => $t->isExpense());
        }

        return $transactions;
    }

    public function deleteTransaction(string $id): void
    {
        $this->repository->deleteById($id);
    }

    public function deleteAllTransactions(): void
    {
        $this->repository->deleteAll();
    }
}
