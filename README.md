# Тестовое задание

## Запуск
* Копируем файл `.env.example` в `.env`
* Заполняем/меняем в `.env` переменные окружения
* Переходим в терминал и вводим комманды:
* * `docker network create indocker-app-network`
* * `docker compose -f docker-compose.yml -f dev.docker-compose.yml build`
* * `docker compose -f docker-compose.yml -f dev.docker-compose.yml up -d`
* * `docker compose exec apache-php bash` - входим в контейенр c PHP
* * `composer install` - устанавливаем composer зависимости
* * `php yii migrate --interactive=0` - запускаем миграции
* * `php yii seed/raw-all` - сидируем таблицы

Проект будет доступен по адресу https://app.indocker.app/order/list/index

## Остановка
* Переходим в терминал и вводим комманду:
* * `docker compose -f docker-compose.yml -f dev.docker-compose.yml stop`

## Удаление
* Переходим в терминал и вводим комманды:
* * `docker compose -f docker-compose.yml -f dev.docker-compose.yml down`
* * `docker network rm indocker-app-network`
* * `docker volume rm test_app_db-data`