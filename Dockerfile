FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Instalar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto
COPY . /var/www/html/

# Instalar dependencias
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

# Configurar Apache para usar la carpeta public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
