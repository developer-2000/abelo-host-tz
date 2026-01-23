-- Таблица категорий
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица статей
CREATE TABLE IF NOT EXISTS `posts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `content` TEXT NOT NULL,
  `image` VARCHAR(512) COMMENT 'URL к изображению (placeholder-сервис, не физический файл)',
  `views` INT UNSIGNED DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_views` (`views`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица связей статей и категорий (many-to-many)
CREATE TABLE IF NOT EXISTS `post_category` (
  `post_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`post_id`, `category_id`),
  FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Индексы для оптимизации запросов
-- Индекс для обратных запросов по категориям
CREATE INDEX IF NOT EXISTS `post_category_category_post` ON `post_category` (`category_id`, `post_id`);

-- Индекс для запросов похожих статей (getSimilar)
CREATE INDEX IF NOT EXISTS `post_category_post_category` ON `post_category` (`post_id`, `category_id`);

-- Составной индекс для сортировки по дате с уникальностью по id
CREATE INDEX IF NOT EXISTS `posts_created_id` ON `posts` (`created_at`, `id`);
