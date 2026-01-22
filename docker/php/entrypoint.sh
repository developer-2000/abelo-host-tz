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

echo "Setup complete. PHP-FPM is running."
# Держим контейнер живым и ждем сигнала завершения
trap 'echo "Stopping PHP-FPM..."; PHP_FPM_PID=$(ps aux | grep "[p]hp-fpm: master process" | awk "{print \$2}" | head -1); [ -n "$PHP_FPM_PID" ] && kill -TERM "$PHP_FPM_PID" 2>/dev/null || true; exit 0' TERM INT
while true; do
    sleep 1
done
