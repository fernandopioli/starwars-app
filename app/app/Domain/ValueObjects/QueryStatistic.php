<?php

namespace App\Domain\ValueObjects;

class QueryStatistic
{
    public function __construct(
        private readonly string $query,
        private readonly int $count,
        private readonly float $percentage
    ) {
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }

    public function toArray(): array
    {
        return [
            'query' => $this->query,
            'count' => $this->count,
            'percentage' => $this->percentage,
        ];
    }
} 