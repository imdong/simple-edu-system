#!/usr/bin/env sh


# 原生方式
php artisan migrate

php artisan passport:install

php artisan db:seed
