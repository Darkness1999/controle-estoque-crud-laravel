# Usar a imagem oficial do PHP 8.2 com Apache
FROM php:8.2-apache

# Instalar dependências do sistema e extensões do PHP necessárias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install \
    pdo_pgsql \
    zip \
    gd

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos da aplicação
COPY . .

# Dar permissões à pasta de storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expor a porta 80
EXPOSE 80