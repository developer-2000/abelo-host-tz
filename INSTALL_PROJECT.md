# Инструкция по запуску проекта

## Требования

- Docker Desktop (для Windows) или Docker + Docker Compose
- Минимум 2GB свободной оперативной памяти

### 1. Клонирование репозитория

```bash
git clone <url-репозитория>
cd project-tz
```

### 2. Настройка окружения

Создайте файл `.env` в корне проекта (можно скопировать из `env.example`):

```env
# Database Configuration
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=abelo_host_tz
DB_USERNAME=root
DB_PASSWORD=root

# MySQL Configuration
MYSQL_PORT=3306

# Application Configuration
APP_PORT=80
APP_ENV=local

# phpMyAdmin Configuration
PMA_PORT=8080
```

### 3. Собрать контейнеры и произвести необходимые установки

```bash
docker-compose up --build -d
```
# 3.1 Проверить логи PHP контейнера (должны быть сообщения о загрузке схемы и сидеров)
docker-compose logs php

Эта команда:
- Соберёт образ PHP-FPM с необходимыми расширениями
- Запустит необходимые сервисы (MySQL, Nginx, PHP-FPM, phpMyAdmin, Node.js)
- Автоматически установит зависимости:
  - Composer (если есть `composer.json` в корне проекта)
  - npm (устанавливается автоматически в Node контейнере при наличии `package.json`)
- Настроит права доступа
- **Автоматически загрузит схему БД** (`database/schema.sql`) при первом запуске
- **Автоматически запустит сидеры** (`database/seed.php`) при первом запуске

**Важно:** 
- Схема БД и сидеры запускаются **только один раз** при первом развертывании
- При последующих запусках (`docker-compose up -d`) данные сохраняются
- Если нужно пересоздать данные, удалите volume: `docker-compose down -v` (осторожно: удалит все данные!)

### 4. Ручное управление БД (опционально)

Если нужно вручную загрузить схему или запустить сидеры:

```bash
# Загрузить схему БД вручную
docker-compose exec -T mysql mysql -u root -proot abelo_host_tz < database/schema.sql

# Запустить сидеры вручную
docker-compose exec php php database/seed.php

# Или выполните SQL через phpMyAdmin (http://localhost:8080)
```

### 6. Доступ к приложению

После успешного запуска приложение будет доступно по адресам:

- **Основное приложение**: http://localhost
- **phpMyAdmin**: http://localhost:8080
  - Логин: `root`
  - Пароль: `root`

## Управление контейнерами

### Запуск

```bash
docker-compose up -d
```

### Остановка

```bash
docker-compose down
```

### Перезапуск

```bash
docker-compose restart
```

### Просмотр статуса

```bash
docker-compose ps
```

### Просмотр логов

```bash
# Все сервисы
docker-compose logs -f

# Конкретный сервис
docker-compose logs -f php
docker-compose logs -f nginx
docker-compose logs -f mysql
```

## Структура сервисов

| Сервис | Порт | Описание |
|--------|------|----------|
| Nginx | 80 | Веб-сервер |
| MySQL | 3306 | База данных |
| phpMyAdmin | 8080 | Управление БД |
| PHP-FPM | 9000 | Обработка PHP |
| Node.js | - | Компиляция SCSS |

## Решение проблем

### Контейнер не запускается

```bash
# Проверьте логи
docker-compose logs [service-name]

# Перезапустите
docker-compose restart [service-name]
```

### Ошибка подключения к БД

1. Убедитесь, что MySQL контейнер запущен: `docker-compose ps`
2. Проверьте переменные в `.env` файле
3. Проверьте логи: `docker-compose logs mysql`

### SCSS не компилируется

1. Убедитесь, что папка `assets/scss/` существует
2. Проверьте логи: `docker-compose logs node`
3. Перезапустите Node контейнер: `docker-compose restart node`
