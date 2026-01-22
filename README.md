# Abelo Host - Тестовое задание

Простой веб-сайт на чистом PHP (без фреймворков) с использованием MySQL и шаблонизатора Smarty. Реализует функционал блога с категориями и постами.

## Технологический стек

- PHP 8.1+
- Шаблонизатор Smarty
- База данных MySQL 8
- Nginx
- Node.js (для компиляции SCSS)

## Структура проекта

```
abelo-host-tz/
├── app/                   # PHP приложение
│   ├── Controllers/       # Контроллеры
│   ├── Models/            # Модели
│   ├── Core/              # Ядро (Router, SmartyInit)
│   └── routes/            # Маршруты (web.php)
├── public/                # Публичная директория
│   ├── index.php          # Точка входа
│   └── assets/            # Статические файлы
│       └── css/            # Скомпилированный CSS
├── templates/             # Шаблоны Smarty
│   ├── layouts/           # Верхний слой
│   ├── pages/             # Шаблоны страниц
│   └── components/        # Компоненты
├── database/              # Работа с БД
│   ├── schema.sql         # Структура БД
│   └── seed.php           # Сидинги
├── assets/                
│   └── scss/              # SCSS файлы
│       ├── main.scss      # Главный файл
│       ├── layouts/       # Стили layouts
│       └── components/    # Стили компонентов
├── configs/               # Конфигурации Smarty
├── var/                   # Кэш и скомпилированные шаблоны
│   ├── templates_c/       # Скомпилированные шаблоны Smarty
│   └── cache/             # Кэш Smarty
└── docker/                # Docker конфигурации
    ├── nginx/             # Конфигурация Nginx
    └── php/               # Конфигурация PHP
```
## Быстрый старт

Инструкция по запуску: [INSTALL_PROJECT.md](INSTALL_PROJECT.md)

### Запуск

```bash
docker-compose up -d
```

**При первом запуске автоматически:**
- Загружается схема БД (`database/schema.sql`)
- Запускаются сидеры (`database/seed.php`) для наполнения тестовыми данными

При последующих запусках (`docker-compose up -d`) данные сохраняются.

### Остановка

```bash
docker-compose down
```

## Доступ к приложению

- **Основное приложение**: http://localhost
- **phpMyAdmin**: http://localhost:8080




