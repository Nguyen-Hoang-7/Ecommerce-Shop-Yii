FROM php:8.4-apache

# Cài các extension cần thiết
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev zip libonig-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip

# Bật mod_rewrite
RUN a2enmod rewrite

# Cài composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Cấu hình DocumentRoot cho frontend
ENV APACHE_DOCUMENT_ROOT /var/www/html/frontend/web

# Cập nhật cấu hình frontend
RUN sed -ri 's!/var/www/html!/var/www/html/frontend/web!g' /etc/apache2/sites-available/000-default.conf \
 && sed -ri 's!<Directory /var/www/>!<Directory /var/www/html/frontend/web/>!g' /etc/apache2/apache2.conf \
 && echo '<Directory /var/www/html/frontend/web>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/frontend.conf \
 && a2enconf frontend

# Tạo alias /admin trỏ đến backend
RUN echo 'Alias /admin /var/www/html/backend/web\n\
<Directory /var/www/html/backend/web>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/backend.conf \
 && a2enconf backend

WORKDIR /var/www/html
