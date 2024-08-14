# Тестовое задание PHP Backend Developer

* [Техническое задание](specification.md)


Должен быть установлен docker и compose, более простой способ - это скачать и установить Docker Desktop https://www.docker.com/products/docker-desktop/

При разработке был использован Docker Desktop v.4.32.0. При разработке была проверена работоспособность приложения на ОС Windows 11 и Ubuntu 24.

Запустить контейнер
```
docker compose up --build -d
```

открыть веб-приложение http://localhost:3000/

По адресу http://localhost:8081/ можно получить доступ к PgAdmin. Данные для входа:
Почта: admin@admin.com
Пароль: password

## Запросы в API

GET без параметров - получить список всех гостей
GET с параметром: id=number - получить данные гостя с id равным number

