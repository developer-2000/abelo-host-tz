<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Entity/Value Object для поста
 */
class Post
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ?string $description = null,
        public readonly string $content = '',
        public readonly ?string $image = null,
        public readonly int $views = 0,
        public readonly ?string $createdAt = null,
    ) {
    }

    /**
     * Создать из массива данных
     *
     * @param array $data Данные поста
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            title: $data['title'],
            description: $data['description'] ?? null,
            content: $data['content'] ?? '',
            image: $data['image'] ?? null,
            views: (int)($data['views'] ?? 0),
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
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'image' => $this->image,
            'views' => $this->views,
            'created_at' => $this->createdAt,
        ];
    }
}
