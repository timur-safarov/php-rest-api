
<p align="center">
    <a href="https://www.php.net/" target="_blank" style="background-color: #4d5994;">
        <img src="https://www.php.net/images/logos/php-logo-white.svg" height="100px">
    </a>
    <h1 align="center">React - CRUD-API JSON-SERVER DataGrid table</h1>
    <br>
</p>

Directions
----------

	api/                    contains rest api methods
	config/                 contains site configs
	assets/                 contains assets definition
	controllers/            contains controllers for request processing
    libraries/              contains different libraries like Curl, Db
    models/                 contains site models
    views/                  contains site templates

Prerequisites
-------------

* Linux
* Apache
* Mysql
* Php
* Postman


Тестовое задание для Middle Php Developer
-------------------------------------------

## Задание

### Можно на чистом php реализовать API пользователей:

1. **Создать открытый Git репозиторий**

2. **Реализовать методы REST API для работы с пользователями**

   - Создание пользователя;
   - Обновление информации пользователя;
   - Удаление пользователя;
   - Авторизация пользователя;
   - Получить информацию о пользователе;

3. **В файле README.md описать реализованные методы**

Instalation for Distribution Linux - Ubuntu
-------------------------------------------

### 0). Перейдите в директорию проекта
* ``` cd /path-php-app/ ```

### 2). Создайте домен для своей папки и включите mod rewrite модуль

### 3). Перезагрузите сервер
* ``` service apache2 restart ```

### 4). Склонируйте репозиторий
* ``` git clone repo ./ ```

### 5). Импортируйте sql базу
* ``` mysql -h localhost -u root -proot your_db_name < /path-php-app/db.sql ```

### 6). Поменяйте реквизиты для базы данных в файле config/common.php

### 7). Запустите сайт в браузере пройдя по адресу указаному в настройках домена

API методы
-------------------------------------------

   - Метод доступа к API Basic Auth
   - Get методы работают как через токен так и через Basic Auth
   - Отсальный методы Delete, Post только через Basic Auth авторизацию
   - Для Basic Auth авторизации логин, пароль или токен можно взять в файле config/common.php
   - или вам нужно зарегистрироваться и использовать свои логин и пароль
   - Для примера есть файл API.postman_collection.json, который можно импортировать в приложение Postman

### 1). Получить список доступных адресов Api /user-api/index_get?token=

### 2). Получить данные пользователя по его id /user-api/user_get?id=100&token=

### 3). Авторизация пользователя, данный метод используеться только при обращении через браузер /user-api/auth_post
   - Создан просто дополнительно чтобы сам сайт тоже через Api работал;

### 4). Создать пользователя /user-api/user-create_post

### 5). Обновить пользователя /user-api/user-update_post

### 6). Удалить пользователя по его id /user-api/user_delete?id=100

