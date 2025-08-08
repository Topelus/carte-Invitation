# Utilise PHP 8.2 avec Apache
FROM php:8.2-apache

# Installe les extensions MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copie ton code dans le dossier du serveur Apache
COPY . /var/www/html/

# Donne les bons droits
RUN chown -R www-data:www-data /var/www/html

# Expose le port 80
EXPOSE 80
