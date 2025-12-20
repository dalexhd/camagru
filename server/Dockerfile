# Use the official PHP image
FROM php:8.2-fpm-alpine

# Set the working directory
WORKDIR /app

# Copy the application code
COPY . .

# Install required PHP extensions
RUN docker-php-ext-install pdo_mysql

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
