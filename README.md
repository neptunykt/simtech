# Начало работы

## Используемая операционная системе и компоненты

## Операционная система

Приложение проверено на Ubuntu 22.04. LTS

Узнать версию Ubuntu можно командой:

**lsb_release -a**

## Компоненты

### Версия php

PHP 8.1.2-1ubuntu2.8

Узнать установленную версию можно командой:

**php -v**


### Версия nginx

nginx/1.18.0 (Ubuntu)

Узнать установленную версию можно командой:

**nginx -v**

### Версия phpadmin

8.0.31-0ubuntu0.22.04.1

Узнать установленную версию можно командой:

**mysqld -version**

### Версия phpmyadmin

phpMyAdmin 5.1.1

Узнать версию можно после установки по урл:

http://localhost/phpmyadmin/doc/html/index.html

## Необходимые компоненты

### Установка Nginx

Nginx — это обратный прокси-сервер. Известно, что это гораздо более эффективный и оптимизированный веб-сервер  по сравнению с Apache.
Имеются свои плюсы и минусы.

**sudo apt-get update**

**sudo apt-get install nginx**

### Установка PHP

Nginx обрабатывает статические данные а обработку скриптов отдает PHP-FPM (Fast Process Manager) PHP FPM запускается как отдельный процесс и взаимодействет с веб-сервером через 9000 порт или сокетный файл.

sudo apt-get install php8.1-fpm
 
Распространенной ошибкой является установка пакета PHP, а не пакета PHP-FPM. Проблема с этим подходом заключается в том, что в отличие от PHP-FPM, пакет PHP устанавливает HTTP-сервер Apache и его процесс httpd, который конфликтует с Nginx.
После завершения установки PHP-FPM проверьте, работает ли он:
 
**sudo systemctl status php8.1-fpm**
 
### Добавление PHP для Nginx

Для корректной работы REST API сайта требуется заменить содержимое файла по пути: 
/etc/nginx/sites-available/default содержимым файла из /ProjectSettings/default

**sudo cp -i simtech/ProjectSettings/default /etc/nginx/sites-available**

### Установка MySql

Система управления базой данных MySql устанавливается командой

**sudo apt-get install mysql-server**

Далее заходим с консоли, задаем пароль для пользователя root:

**sudo mysql**

**mysql> alter user 'root'@'localhost' identified with mysql_native_password by 'ваш_пароль';**

**mysql> exit**

Создаем базу данных simtech, пользователя user и даем ему все права на базу simtech:

**sudo mysql -u root -p**

**mysql> create database simtech;**

**mysql> create user 'user'@'localhost' identified with mysql_native_password by 'ваш_пароль';**

**mysql> grant all on simtech.* to 'user'@'localhost';**

**mysql> exit**

### Установка phpMyAdmin

phpMyAdmin - это приложение написанное на PHP и обеспечивающее полноценную, в том числе удаленную, работу с базами данных MySQL через браузер, на вопрос выбора сервера оставить галочки пустыми, на настройку phpmyadmin выбрать отмена.

**sudo apt install phpmyadmin**

После установки надо скопировать файл phpmyadmin.conf из папки ProjectSettings/phpmayadmin.conf в папку /etc/nginx/snippets/phpmyadmin.conf

**sudo cp -i simtech/ProjectSettings/phpmyadmin.conf /etc/nginx/snippets/**

При выборе конфигурации сервера нужно убрать все галочки, на вопрос конфигурирования базы данных для phpmyadmin - выбираем нет.
Далее в браузере набираем: http://localhost/phpmyadmin и заходим под пользователем user

### Установка Composer

Composer — это пакетный менеджер уровня приложений для языка программирования PHP, который предоставляет средства по управлению зависимостями в PHP-приложении.

**php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"**

**php composer-setup.php --install-dir=/usr/local/bin --filename=composer**

### Размещение кода на сайте

Нужно исправить логин и пароль, который вы задали для базы simtech пользователю для доступа в базу в файле inc/config. Выполняем команду в корне проекта (папка simtech), он установит все зависимости:
 
**composer install**

Для записи файлов обратной связи в папку uploads нужны права на запись.
Далее нужно скопировать весь код в папку html командой из корневой папки проекта:

**sudo cp * -R simtech /var/www/html**

**sudo chmod 777 -R /var/www/html**
  
и перезапускаем nginx:

**sudo systemctl reload nginx**

и зайти на адрес (главная страница сайта): http://localhost

Для удобства создания таблиц используется сваггер, где по нажатию на кнопку выполняется GET запрос, устанавливающий таблицы в базе, также можно установить базу из бекапа, который находится в ProjectSettings/db_backup.sql.
На главной странице сайта имеется инструкция по запуску юнит-тестов, логина и пароля для доступа к записям форм обратной связи.

## Дерево проекта:
```
├─── README.md - README
├─── phpunit.xml - файл настройки юнит-тестов
├─── index.php - точка запуска приложения
├─── composer.json - файл для пакетного менеджера уровня приложений
├─── composer.lock - файл для сохранения списка установленных зависимостей и их версии
├─── .gitignore - файл для исключения файлов git репозитория
├─── Controller - папка контроллеров приложения
|      ├── Api - файлы контроллеров приложения
|      └── documentation - папка для swagger
| 
│──── inc - папка для настроек сайта 
│      ├── captcha.php - настройки каптча
│      ├── config.php - настройки для подключения к бд
│      ├── mailConfig.php - настройки для отправки почтовых сообщений
│      └── routes.php - файл настроек для роутера
|
├──── ProjectSettings - папка для настроек приложений, инструкций сайта
│      ├── db_backup.sql - бекап базы данных
│      ├── default - настройки nginx
│      ├── mail.txt - инструкция для отпарвки почтовых сообщений
|      ├── phpmyadmin.conf - настройки приложения для администрирования СУБД
|      └── recaptcha.txt - инструкция для настройки recaptcha
│   
├──── Site - папка с кодом верстки и js-скриптов сайта
│      ├── Feedbacks - папка страницы отзывов (записей обратной формы)
│      ├── Footer - папка футера сайта
│      ├── Header - папка навигационной панели сайта
│      ├── Home - папка домашней страницы
|      ├── Shared - общая папка сайта
|      └── uploads - папка для загруженных файлов
│   
├──── src - папка с кодом сайта  
|      ├── DAL - папка Data access layer (программный слой кода для работы с бд) 
|      ├── Models - папка моделей
|      ├── Router - папка роутера
|      └── Services - папка программного слой сервисов сайта
|
├──── Tests   
|      ├── e2e - End to end тестирование
|      ├── Integration - интеграционные тесты
|      └── Unit - юнит-тесты

```    

 














