FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
	libpng-dev \
	libcurl4-openssl-dev \
	libsqlite3-dev \
	libzip-dev \
	&& docker-php-ext-install pdo_mysql curl gd zip \
	&& a2enmod rewrite \
	&& rm -rf /var/lib/apt/lists/*

ENV APACHE_DOCUMENT_ROOT=/var/www/html

RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/include/db/ \
	&& chmod -R 775 /var/www/html/include/db/

COPY docker-entrypoint.sh /usr/local/bin/
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh \
	&& chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
