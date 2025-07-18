FROM php:8.2-apache

# Cài các extension cần thiết
RUN docker-php-ext-install pdo pdo_mysql mysqli

# RUN a2enmod rewrite

# Tùy chọn: hiển thị lỗi PHP (debug)
RUN echo "display_errors=On\nerror_reporting=E_ALL" > /usr/local/etc/php/conf.d/docker-php.ini
