# Nginx PHP MySQL PDO

Docker с Nginx, PHP-FPM, Composer, MySQL, PDO and PHPMyAdmin.

___

## Необходимые установки

На данный момент этот проект создан для работы в Unix `(Linux/MacOS)`. Возможно он будет работать на Windows 10.

Необходимые программы

* [Git](https://git-scm.com/downloads)
* [Docker](https://docs.docker.com/engine/installation/)
* [Docker Compose](https://docs.docker.com/compose/install/)

Проверьте установлен ли`docker-compose` следующей командой: 

```sh
which docker-compose
```
Если нет, то установите его.

### Images

* [Nginx](https://hub.docker.com/_/nginx/)
* [MySQL](https://hub.docker.com/_/mysql/)
* [PHP-FPM](https://hub.docker.com/r/nanoninja/php-fpm/)
* [Composer](https://hub.docker.com/_/composer/)
* [PHPMyAdmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin/)
* [Generate Certificate](https://hub.docker.com/r/jacoelho/generate-certificate/)

В этом проекте используются следующие порты:

| Server     | Port |
|------------|------|
| MySQL      | 8989 |
| PHPMyAdmin | 8080 |
| Nginx      | 8000 |
| Nginx SSL  | 3000 |

___

## Копирование проекта

Установите [Git](http://git-scm.com/book/en/v2/Getting-Started-Installing-Git), загрузите репозиторий локально

```sh
git clone https://github.com/Lelya/docker-nginx-php-mysql-pdo.git
```

Перейдите в папку проекта:

```sh
cd docker-nginx-php-mysql-pdo
```

###  Дерево проекта

```sh
.
├── Makefile
├── README.md
├── data
│   └── db
│       ├── dumps
│       └── mysql
├── doc
├── docker-compose.yml
├── etc
│   ├── nginx
│   │   ├── default.conf
│   │   └── default.template.conf
│   ├── php
|   |   └── Dockerfile
│   │   └── php.ini
│   └── ssl
└── web
    ├── app
    │   ├── composer.json.dist
    │   ├── src
    │   │   └── Init.php
    └── public
        └── index.php
```

___

## Конфигурация Nginx с SSL Certificates

Вы можете изменить имя сервера отредоктировав `.env` file.

Если вы изменили имя сервера, то не забудьте добавить его в `/etc/hosts` файл.

1. Генерация SSL certificates

    ```sh
    source .env && sudo docker run --rm -v $(pwd)/etc/ssl:/certificates -e "SERVER=$NGINX_HOST" jacoelho/generate-certificate
    ```

2. Конфигурация Nginx

    Не изменяйте `etc/nginx/default.conf` файл, он перезаписывается с `etc/nginx/default.template.conf`

    Отредактируйте `etc/nginx/default.template.conf` и расскоментируйте раздел ssl:

    ```sh
    # server {
    #     server_name ${NGINX_HOST};
    #
    #     listen 443 ssl;
    #     fastcgi_param HTTPS on;
    #     ...
    # }
    ```

___

## Запуск приложения

1. Скопируйте конфигурационный файл composer.json: 

    ```sh
    cp web/app/composer.json.dist web/app/composer.json
    ```

2. Запуск приложения :

    ```sh
    sudo docker-compose up -d
    ```

    **Это может занять несколько минут, пока подгрузятся все зависимости**

    ```sh
    sudo docker-compose logs -f # Follow log output
    ```

3. Откройте в браузере:

    * [http://localhost:8000](http://localhost:8000/)
    * [https://localhost:3000](https://localhost:3000/) ([HTTPS](#configure-nginx-with-ssl-certificates) не сконфигурирован по умолчанию)
    * [http://localhost:8080](http://localhost:8080/) PHPMyAdmin (username: dev, password: dev)

4. Остановка и очистка сервисов

    ```sh
    sudo docker-compose down -v
    ```


___

## Docker commands

### Установка package с composer

```sh
sudo docker run --rm -v $(pwd)/web/app:/app composer require symfony/yaml
```

### Обновление PHP зависимостей с composer

```sh
sudo docker run --rm -v $(pwd)/web/app:/app composer update
```

### Генерация PHP API documentation

```sh
sudo docker-compose exec -T php php -d memory_limit=256M -d xdebug.profiler_enable=0 ./app/vendor/bin/apigen generate app/src --destination ./app/doc
```

### Тестирование PHP application with PHPUnit

```sh
sudo docker-compose exec -T php ./app/vendor/bin/phpunit --colors=always --configuration ./app
```

### Стандартизация кода с [PSR2](http://www.php-fig.org/psr/psr-2/)

```sh
sudo docker-compose exec -T php ./app/vendor/bin/phpcbf -v --standard=PSR2 ./app/src
```

### Проверка стандартизации кода с [PSR2](http://www.php-fig.org/psr/psr-2/)

```sh
sudo docker-compose exec -T php ./app/vendor/bin/phpcs -v --standard=PSR2 ./app/src
```

### Анализ кода с [PHP Mess Detector](https://phpmd.org/)

```sh
sudo docker-compose exec -T php ./app/vendor/bin/phpmd ./app/src text cleancode,codesize,controversial,design,naming,unusedcode
```

### Проверка установки всех PHP расширений

```sh
sudo docker-compose exec php php -m
```

___
