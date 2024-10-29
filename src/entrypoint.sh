#!/bin/sh
if [ ! -d "/var/www/html/vendor" ]; then
  composer install --prefer-dist --no-interaction
  chown -R www-data:www-data /var/www/html
  chmod -R 755 /var/www/html/vendor/*  
fi

until php artisan migrate --force; do
  echo "Esperando pelo banco de dados..."
  sleep 5
done

until php artisan db:seed; do
  echo "Esperando pelo banco de dados..."
  sleep 5
done