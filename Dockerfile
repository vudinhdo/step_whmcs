# Sử dụng image PHP 8.1 FPM làm base
FROM php:8.1-fpm

# Copy file composer để tận dụng caching (nếu có)
COPY composer.lock composer.json /var/www/

# Thiết lập thư mục làm việc cho ứng dụng
WORKDIR /var/www

# Cài đặt các thư viện hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libgd-dev

# Xóa cache apt để giảm dung lượng image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Cài đặt các extension PHP cần cho Laravel
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-external-gd && docker-php-ext-install gd
RUN docker-php-ext-install bcmath

# Cài đặt Composer (trình quản lý package cho PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Tạo user mới để không chạy ứng dụng bằng user root
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

# Copy mã nguồn Laravel vào image
COPY . /var/www
# (Tuỳ chọn) Thiết lập quyền sở hữu mã nguồn cho user www
COPY --chown=www:www . /var/www

# Chuyển sang sử dụng user không phải root
USER www

# Mở cổng 9000 và chạy PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
