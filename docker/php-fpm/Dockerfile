# Используем официальный образ PHP 8.2 с поддержкой FPM
FROM php:8.2-fpm

WORKDIR "/application"

# Устанавливаем зависимости для сборки pdo_pgsql
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Копируем исходный код в контейнер
COPY . /var/www/html/

# Обеспечиваем корректные права на файлы
RUN chown -R www-data:www-data /var/www/html
