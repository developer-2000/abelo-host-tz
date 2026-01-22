<?php

declare(strict_types=1);

/**
 * Скрипт для наполнения базы данных тестовыми данными
 * Запускается автоматически при первом развертывании проекта
 */

// Подключаем автозагрузчик Composer для использования DatabaseConfig
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\DatabaseConfig;

// Получаем параметры подключения из централизованного конфига
$params = DatabaseConfig::getParams();

try {
    $pdo = new PDO(
        DatabaseConfig::getDsn(),
        $params['username'],
        $params['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage() . "\n");
}

echo "Начинаем наполнение базы данных...\n\n";

// Функция для генерации URL изображения из placeholder-сервиса
// Используем фиксированный ID изображения, чтобы оно не менялось при перезагрузке
function generatePostImage(int $postIndex): string
{
    $width = rand(800, 1200);
    $height = rand(600, 800);
    // Используем индекс статьи + смещение для получения фиксированного ID изображения
    // ID от 1 до 1000 в picsum.photos
    $imageId = ($postIndex % 1000) + 1;
    
    // Используем picsum.photos с фиксированным ID для стабильности изображения
    return "https://picsum.photos/id/{$imageId}/{$width}/{$height}";
}

// Функция для генерации случайной даты в прошлом
function generateRandomDate(): string
{
    $start = strtotime('-6 months');
    $end = time();
    $random = rand($start, $end);
    return date('Y-m-d H:i:s', $random);
}

// Массив категорий
$categories = [
    ['name' => 'Технологии', 'description' => 'Статьи о современных технологиях и IT-инновациях'],
    ['name' => 'Программирование', 'description' => 'Полезные материалы о разработке программного обеспечения'],
    ['name' => 'Дизайн', 'description' => 'Идеи и тренды в веб-дизайне и UI/UX'],
    ['name' => 'Бизнес', 'description' => 'Советы по ведению бизнеса и предпринимательству'],
    ['name' => 'Образование', 'description' => 'Материалы для обучения и саморазвития'],
    ['name' => 'Новости', 'description' => 'Актуальные новости из мира технологий'],
    ['name' => 'Обзоры', 'description' => 'Обзоры инструментов, сервисов и продуктов'],
    ['name' => 'Туториалы', 'description' => 'Пошаговые руководства и инструкции']
];

// Массив заголовков статей
$postTitles = [
    'Введение в PHP 8.1: новые возможности и улучшения',
    'JavaScript ES2024: что нового в языке',
    'Основы работы с Docker для начинающих',
    'Современные подходы к веб-дизайну',
    'Как создать успешный стартап: практические советы',
    'Изучение программирования: с чего начать',
    'Новости из мира искусственного интеллекта',
    'Обзор лучших инструментов для разработчиков',
    'Пошаговое руководство по настройке сервера',
    'React vs Vue: какой фреймворк выбрать',
    'Основы базы данных MySQL',
    'Секреты производительности веб-приложений',
    'Современные тренды в UI/UX дизайне',
    'Как масштабировать бизнес в цифровую эпоху',
    'Онлайн-образование: будущее обучения',
    'Последние обновления в мире технологий',
    'Сравнение облачных сервисов: AWS vs Azure',
    'Туториал по созданию REST API',
    'Основы безопасности веб-приложений',
    'Как оптимизировать работу команды разработки',
    'Введение в микросервисную архитектуру',
    'Современные практики DevOps',
    'Обзор популярных систем управления контентом',
    'Как выбрать правильный стек технологий',
    'Основы работы с Git и GitHub',
    'Создание адаптивных веб-сайтов',
    'Введение в машинное обучение',
    'Лучшие практики написания чистого кода',
    'Как организовать эффективный workflow',
    'Обзор современных CSS-фреймворков'
];

// Массив описаний статей
$postDescriptions = [
    'Краткое описание статьи о современных технологиях и их применении в реальных проектах.',
    'Полезная информация для разработчиков, которые хотят улучшить свои навыки.',
    'Практические советы и рекомендации от опытных специалистов.',
    'Подробный разбор актуальных тем в мире IT и программирования.',
    'Интересные факты и статистика о развитии технологий.',
    'Руководство для начинающих разработчиков с примерами кода.',
    'Обзор новых инструментов и технологий для профессионалов.',
    'Советы по оптимизации и улучшению производительности.',
    'Анализ современных трендов и их влияние на индустрию.',
    'Практические примеры и кейсы из реальных проектов.'
];

// Массив текстов статей
$postContents = [
    'Полный текст статьи о современных технологиях. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
    'Детальное описание темы с примерами и практическими советами. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    'Подробный разбор вопроса с анализом различных подходов. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
    'Практическое руководство с пошаговыми инструкциями. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.',
    'Анализ современных решений и их применение. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.'
];

// Сидинг категорий
echo "Создание категорий...\n";
$stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)");
$categoryIds = [];

foreach ($categories as $category) {
    $stmt->execute([
        'name' => $category['name'],
        'description' => $category['description']
    ]);
    $categoryIds[] = $pdo->lastInsertId();
}

echo "Создано категорий: " . count($categoryIds) . "\n\n";

// Сидинг статей
echo "Создание статей...\n";
$stmt = $pdo->prepare("
    INSERT INTO posts (title, description, content, image, views, created_at) 
    VALUES (:title, :description, :content, :image, :views, :created_at)
");
$stmtRelation = $pdo->prepare("INSERT INTO post_category (post_id, category_id) VALUES (:post_id, :category_id)");

$postsCount = rand(20, 35); // 20-35 статей
$createdPosts = 0;

for ($i = 0; $i < $postsCount; $i++) {
    $title = $postTitles[array_rand($postTitles)];
    $description = $postDescriptions[array_rand($postDescriptions)];
    $content = str_repeat($postContents[array_rand($postContents)] . ' ', rand(3, 8));
    $image = generatePostImage($i); // Передаем индекс для фиксированного ID изображения
    $views = rand(0, 500);
    $createdAt = generateRandomDate();
    
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'content' => trim($content),
        'image' => $image,
        'views' => $views,
        'created_at' => $createdAt
    ]);
    
    $postId = $pdo->lastInsertId();
    
    // Связываем статью с категориями (1-3 случайные категории)
    $numCategories = rand(1, min(3, count($categoryIds)));
    
    // array_rand возвращает массив ключей или один ключ, если запрашиваем 1 элемент
    if ($numCategories === 1) {
        $selectedCategoryIndex = array_rand($categoryIds);
        $selectedCategories = [$selectedCategoryIndex];
    } else {
        $selectedCategories = array_rand($categoryIds, $numCategories);
    }
    
    foreach ($selectedCategories as $categoryIndex) {
        $stmtRelation->execute([
            'post_id' => $postId,
            'category_id' => $categoryIds[$categoryIndex]
        ]);
    }
    
    $createdPosts++;
}

echo "Создано статей: {$createdPosts}\n\n";

// Статистика
$stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
$categoriesCount = $stmt->fetch()['count'];

$stmt = $pdo->query("SELECT COUNT(*) as count FROM posts");
$postsCount = $stmt->fetch()['count'];

$stmt = $pdo->query("SELECT COUNT(*) as count FROM post_category");
$relationsCount = $stmt->fetch()['count'];

echo "=== Статистика ===\n";
echo "Категорий: {$categoriesCount}\n";
echo "Статей: {$postsCount}\n";
echo "Связей статей с категориями: {$relationsCount}\n";
echo "\nБаза данных успешно заполнена!\n";
