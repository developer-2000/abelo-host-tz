#!/bin/sh
set -e

# Настраиваем PHP-FPM сразу, чтобы можно было запустить его раньше
echo "Configuring PHP-FPM to listen on 0.0.0.0:9000..."
sed -i 's/listen = 127.0.0.1:9000/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf || true

# Запускаем PHP-FPM в фоне сразу, чтобы healthcheck мог начать работать
echo "Starting PHP-FPM in background..."
php-fpm -D

# Ждем, пока PHP-FPM запустится
sleep 2

# Проверяем, что PHP-FPM запущен
if ! nc -z localhost 9000 2>/dev/null; then
    echo "ERROR: PHP-FPM failed to start!"
    exit 1
fi

echo "PHP-FPM is running. Continuing with setup..."

# Ждем MySQL
echo "Waiting for MySQL to be ready..."
until nc -z mysql 3306 2>/dev/null; do
  echo "MySQL is unavailable - sleeping"
  sleep 2
done
echo "MySQL is ready"

# Устанавливаем Composer зависимости
echo "Installing Composer dependencies..."
if [ -f "composer.json" ]; then
    if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
        composer install --no-interaction --prefer-dist --optimize-autoloader
    fi
fi

# Устанавливаем npm зависимости (если есть package.json)
echo "Installing npm dependencies..."
if [ -f "package.json" ]; then
    if [ ! -d "node_modules" ]; then
        npm install
    fi
fi

# Устанавливаем права доступа
echo "Setting permissions..."
chown -R www-data:www-data /var/www 2>/dev/null || true
chmod -R 755 /var/www 2>/dev/null || true

# Проверяем и загружаем схему БД (если таблиц нет)
echo "Checking database schema..."
if [ -f "database/schema.sql" ]; then
    # Проверяем через PHP, существует ли таблица categories
    TABLE_EXISTS=$(php -r "
    try {
        \$pdo = new PDO(
            'mysql:host=${DB_HOST:-mysql};dbname=${DB_DATABASE:-abelo_host_tz};charset=utf8mb4',
            '${DB_USERNAME:-root}',
            '${DB_PASSWORD:-root}',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        \$stmt = \$pdo->query(\"SHOW TABLES LIKE 'categories'\");
        echo \$stmt->rowCount() > 0 ? '1' : '0';
    } catch (Exception \$e) {
        echo '0';
    }
    " 2>/dev/null || echo "0")
    
    if [ "$TABLE_EXISTS" = "0" ]; then
        echo "Loading database schema..."
        php -r "
        try {
            \$pdo = new PDO(
                'mysql:host=${DB_HOST:-mysql};dbname=${DB_DATABASE:-abelo_host_tz};charset=utf8mb4',
                '${DB_USERNAME:-root}',
                '${DB_PASSWORD:-root}',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            \$sql = file_get_contents('database/schema.sql');
            \$pdo->exec(\$sql);
            echo 'Database schema loaded successfully.\n';
        } catch (Exception \$e) {
            echo 'Warning: Failed to load schema.sql: ' . \$e->getMessage() . '\n';
            exit(1);
        }
        " 2>&1 || {
            echo "Warning: Failed to load schema.sql. You may need to load it manually."
        }
    else
        echo "Database schema already exists. Skipping..."
    fi
fi

# Проверяем и запускаем сидеры (если данных нет)
echo "Checking database seeding..."
if [ -f "database/seed.php" ]; then
    # Проверяем через PHP, есть ли данные в таблице categories
    CATEGORIES_COUNT=$(php -r "
    try {
        \$pdo = new PDO(
            'mysql:host=${DB_HOST:-mysql};dbname=${DB_DATABASE:-abelo_host_tz};charset=utf8mb4',
            '${DB_USERNAME:-root}',
            '${DB_PASSWORD:-root}',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        \$stmt = \$pdo->query('SELECT COUNT(*) as count FROM categories');
        \$result = \$stmt->fetch(PDO::FETCH_ASSOC);
        echo \$result['count'] ?? '0';
    } catch (Exception \$e) {
        echo '0';
    }
    " 2>/dev/null || echo "0")
    
    if [ "$CATEGORIES_COUNT" = "0" ] || [ -z "$CATEGORIES_COUNT" ]; then
        echo "Running database seeders..."
        php database/seed.php 2>&1 || {
            echo "Warning: Failed to run seed.php. You may need to run it manually."
        }
        echo "Database seeding completed."
    else
        echo "Database already seeded ($CATEGORIES_COUNT categories found). Skipping..."
    fi
fi

echo "Setup complete. PHP-FPM is running."
# Держим контейнер живым и ждем сигнала завершения
trap 'echo "Stopping PHP-FPM..."; PHP_FPM_PID=$(ps aux | grep "[p]hp-fpm: master process" | awk "{print \$2}" | head -1); [ -n "$PHP_FPM_PID" ] && kill -TERM "$PHP_FPM_PID" 2>/dev/null || true; exit 0' TERM INT
while true; do
    sleep 1
done
