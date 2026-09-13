<?php

declare(strict_types=1);

namespace App;

final readonly class Transaction
{
    public function __construct(
        // type можно сделать enum. Будет намного проще чекни что это
        public string $type,
        public float $amount,
        public string $category,
        public string $comment,
        public string $id,
        public string $date,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function isIncome(): bool
    {
        return $this->type === 'income';
    }

    public function isExpense(): bool
    {
        return $this->type === 'expense';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'amount' => $this->amount,
            'category' => $this->category,
            'comment' => $this->comment,
            'date' => $this->date,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['type'],
            (float) $data['amount'],
            $data['category'],
            $data['comment'] ?? '',
            $data['id'],
            $data['date']
        );
    }

    public static function create(
        string $type,
        float $amount,
        string $category,
        string $comment,
        ?string $date = null
    ): self {
        $id = uniqid('', true);
        $date = $date ?? date('Y-m-d H:i');

        return new self($type, $amount, $category, $comment, $id, $date);
    }
}