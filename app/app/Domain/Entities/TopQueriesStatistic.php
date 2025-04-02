<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\QueryStatistic;

use DateTime;

class TopQueriesStatistic
{
   
    public function __construct(
        private readonly array $queries,
        private readonly DateTime $updatedAt
    ) {
    }

    public function getQueries(): array
    {
        return $this->queries;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'queries' => array_map(fn(QueryStatistic $query) => $query->toArray(), $this->queries),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s')
        ];
    }
} 