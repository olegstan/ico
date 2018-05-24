#!/bin/bash

composer dump-autoload;
php artisan cache:clear;
php artisan optimize;
php artisan laroute:generate;
php artisan migrate:refresh;
php artisan db:seed;