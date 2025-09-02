FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd

# RUN useradd -u 1000 -m user

# USER user

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy entire project
COPY . .

# Run post-install scripts after artisan is  available
RUN composer run-script post-autoload-dump

# Set proper permissions
# Always ensure www-data can write to storage and cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R ug+rwX /var/www/storage /var/www/bootstrap/cache

# Copy and set up entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# ARG APP_ENV=production
# RUN if [ "$APP_ENV" = "production" ]; then \
#         chown -R www-data:www-data /var/www \
#         && chmod -R 755 /var/www/storage \
#         && chmod -R 755 /var/www/bootstrap/cache ; \
#     else \
#         chown -R www-data:www-data /var/www \
#         && chmod -R 777 /var/www/storage/ /var/www/bootstrap/cache ; \
#     fi

# Set proper permissions (former code)
# RUN chown -R www-data:www-data /var/www \
#     && chmod -R 755 /var/www/storage \
#     && chmod -R 755 /var/www/bootstrap/cache

# RUN chown -R www-data:www-data .

# Configure PHP-FPM
RUN echo "pm.max_children = 50" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.start_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.min_spare_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_spare_servers = 35" >> /usr/local/etc/php-fpm.d/www.conf

EXPOSE 9000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]