<?php

namespace App\Domain\ValueObjects;

class EntityReference
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $name = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

    public static function fromUrl(string $url): self
    {
        preg_match('/\/(\d+)\/?$/', $url, $matches);
        return new self(
            id: $matches[1] ?? '0'
        );
    }
} 