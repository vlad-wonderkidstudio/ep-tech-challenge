FROM php:8.1-fpm

RUN apt-get update --fix-missing \
    && apt-get install -y curl wget zip unzip git \
    && rm -rf /var/lib/apt/lists/*

# Install system dependencies.
RUN apt-get update && apt-get install -y \
    git \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions.
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql

# Install Composer globally.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Create a directory for your Laravel application.
WORKDIR /usr/share/nginx/www

# PHPRedis
RUN pecl install redis

# Install NodeJs v16 and dependencies
ENV NVM_DIR /root/.nvm
ENV NODE_VERSION v16.17.0
ENV NODE_PATH $NVM_DIR/versions/node/$NODE_VERSION/bin
ENV PATH $NODE_PATH:$PATH
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash
RUN . $NVM_DIR/nvm.sh \
    && nvm install ${NODE_VERSION}

CMD cd /usr/share/nginx/www \
    && cp -R -u -p ./.env.example ./.env \
    && composer install --no-interaction --optimize-autoloader \
    && php artisan key:generate \
    && php artisan migrate \
    && npm install \
    && npm run dev \
    && php-fpm --nodaemonize
