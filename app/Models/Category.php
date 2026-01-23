<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Entity/Value Object для категории
 */
class Category
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description = null,
        public readonly ?string $createdAt = null,
    ) {
    }

    /**
     * Создать из массива данных
     *
     * @param array $data Данные категории
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            name: $data['name'],
            description: $data['description'] ?? null,
            createdAt: $data['created_at'] ?? null,
        );
    }

    /**
     * Преобразовать в массив
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->createdAt,
        ];
    }
}
