# Use the official PHP image
FROM php:8.2-fpm-alpine

# Set the working directory
WORKDIR /app

# Copy the application code
COPY . .

# Install required PHP extensions
RUN docker-php-ext-install pdo_mysql

# Install GD library for image processing
RUN apk add --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Configure PHP for larger uploads
RUN echo "upload_max_filesize = 50M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 50M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini

# Create uploads directory
RUN mkdir -p public/img/uploads
RUN mkdir -p public/img/profiles

# Set permissions
RUN chmod -R 777 public/img
RUN chmod 777 public/img/uploads
RUN chmod 777 public/img/profiles

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
