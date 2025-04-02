<?php

namespace App\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueryPerformed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $query,
        public readonly string $type
    ) {
    }
} 