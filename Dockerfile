# Sử dụng PHP 8.1 FPM (Debian) làm base image
FROM php:8.1-fpm

# Thiết lập biến môi trường (nếu cần)
ENV DEBIAN_FRONTEND=noninteractive

# Cập nhật và cài đặt các gói phụ thuộc cần thiết
# - gnupg2, curl: hỗ trợ thêm repo NodeSource
# - git, unzip: cần cho Composer cài package
# - libzip-dev, libicu-dev: hỗ trợ PHP extension zip và intl
# - libpng-dev, libjpeg62-turbo-dev, libfreetype6-dev: hỗ trợ PHP GD (xử lý ảnh)
# - libonig-dev: hỗ trợ PHP mbstring (xử lý chuỗi đa byte)
RUN apt-get update && apt-get install -y \
    gnupg2 curl git unzip \
    libzip-dev libicu-dev libonig-dev \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev

# Cài đặt Node.js 22.x LTS + npm 10.x từ NodeSource:contentReference[oaicite:3]{index=3}
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Xác minh phiên bản Node và npm (tùy chọn)
RUN node -v && npm -v

# Cài đặt các extension PHP cần cho Laravel/Filament:contentReference[oaicite:4]{index=4}:
# pdo_mysql (kết nối MySQL), bcmath, mbstring, zip, intl, gd, opcache, và redis (qua PECL)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
       pdo_mysql bcmath mbstring zip intl gd opcache \
    && pecl install redis \
    && docker-php-ext-enable redis

# Cài Composer từ image chính thức
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Tạo user "www" (UID 1000) để chạy ứng dụng, tránh chạy bằng root
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www \
    && mkdir -p /var/www/html && chown -R www:www /var/www/html

# Thiết lập thư mục làm việc và user
WORKDIR /var/www/html
USER www

# Mặc định PHP-FPM sẽ chạy trên cổng 9000, không cần CMD vì ảnh PHP-FPM có sẵn entrypoint
EXPOSE 9000
