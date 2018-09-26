FROM php:7.0-fpm

RUN docker-php-ext-install pdo_mysql \
                           json

RUN echo "alias phinx='php src/vendor/bin/phinx'" >> ~/.bashrc
