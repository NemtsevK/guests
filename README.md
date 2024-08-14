# Тестовое задание PHP Backend Developer

Автор: [Константин Немцев](https://github.com/NemtsevK)

* [Техническое задание](specification.md)

При разработке была использована контейнеризация приложения с помощью Docker 27.1.1 и Docker Compose v2.29.1. Использован язык программирования PHP 8.2 и база данных PostgreSQL 15. При разработке была проверена работоспособность приложения на ОС Windows 11 и Ubuntu 24.

Веб-приложение представляет собой одностраничный веб-сайт, на котором пользователь может получить добавить гостя, получить список гостей, изменить данные гостя и удалить гостя.

Команда для запуска контейнера с приложением:

```
docker compose up --build -d
```

После запуска можно открыть веб-приложение в браузере по адресу: http://localhost:3000/

Также можно доступ к PgAdmin по адресу http://localhost:8081/

Данные для входа:

**Почта:** admin@admin.com

**Пароль:** password

## Запросы в API

* Получить список всех гостей - GET без параметров
* Получить данные гостя с id равным number - GET с параметром в адресе: id=number
* Добавить данные гостя - POST с параметрами в body: first_name=string&last_name=string&phone=string&email=string&country=string -
* Обновить данные гостя - PUT с параметрами в body: id=number&first_name=string&last_name=string&phone=string&email=string&country=string
* Удалить данные гостя - DELETE с параметром в body: id=number
