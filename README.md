Системные требования
------------

Docker, Docker Compose

Запуск проекта
------------
Скопировать .env.example в .env. При необходимости поменять значения переменных. 

Запуск контейнеров

    docker compose up -d
    
Установка composer-пакетов

    docker compose exec app composer i    

Запуск миграций

    docker compose exec app php yii migrate --interactive=0
    
Для удобства можно загрузить фикстуры (пользователь user_1, пароль password_1)

    docker compose exec app composer seed

Для запуска тестов

    docker compose exec app composer test

Для запуска phpcsfixer

    docker compose exec app composer cs-fixer-check
    docker compose exec app composer cs-fixer-fix

Проект по-умолчанию доступен по ссылке http://localhost:8001
