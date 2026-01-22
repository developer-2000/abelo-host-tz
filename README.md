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
│   └── Core/              # Ядро (Database, SmartyInit)
├── public/                # Публичная директория
│   ├── index.php          # Точка входа
│   └── assets/            # Статические файлы
├── templates/             # Шаблоны Smarty
│   ├── layouts/           # Верхний слой
│   ├── pages/             # Шаблоны страниц
│   └── components/        # Компоненты
├── database/              # Работа с БД
│   ├── schema.sql         # Структура БД
│   └── seed.php           # Сидинги
├── assets/                
│   └── scss/              # SCSS файлы
└── docker/                # Docker конфигурации
```
## Быстрый старт

Инструкция по запуску: [INSTALL_PROJECT.md](INSTALL_PROJECT.md)

### Запуск

```bash
docker-compose up -d
```

### Остановка

```bash
docker-compose down
```

## Доступ к приложению

- **Основное приложение**: http://localhost
- **phpMyAdmin**: http://localhost:8080




