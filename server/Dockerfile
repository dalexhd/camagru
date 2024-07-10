# Use the official PHP image
FROM php:8.2-fpm-alpine

# Set the working directory
WORKDIR /app

# Copy the application code
COPY . .

# Install required PHP extensions
RUN docker-php-ext-install pdo_mysql

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
