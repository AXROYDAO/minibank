<?php

declare(strict_types=1);

namespace App;

use PDO;

class TransactionRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Transaction $transaction): void
    {
        $sql = 'INSERT INTO transactions (id, type, amount, created_at, category, comment) VALUES (:id, :type, :amount, :created_at, :category, :comment)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $transaction->getId(),
            'type' => $transaction->getType(),
            'amount' => $transaction->getAmount(),
            'created_at' => $transaction->getDate(),
            'category' => $transaction->getCategory(),
            'comment' => $transaction->getComment()
        ]);
    }

    public function findAll(): array
    {
        $sql = 'SELECT * FROM transactions ORDER BY created_at DESC';
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll();
        $transactions = [];
        foreach ($rows as $row) {
            $transactions[] = new Transaction(
                $row['type'],
                (float)$row['amount'],
                $row['category'],
                $row['comment'],
                $row['id'],
                $row['created_at'],
            );
        }
        return $transactions;
    }

    public function deleteById(string $id): void
    {
        $sql = 'DELETE FROM transactions WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function deleteAll(): void
    {
        $sql = 'DELETE FROM transactions';
        $this->pdo->exec($sql);
    }
}