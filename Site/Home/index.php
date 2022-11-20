<!DOCTYPE html>
<head>
    <title>Simtech</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./../Shared/style.css">  
    <link rel="stylesheet" href="./../Header/style.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script type="text/javascript" src="./../Header/script.js"></script>
    <script type="text/javascript" src="./../Shared/script.js"></script>
    <script type="text/javascript" src="./js/script.js"></script>
    <link rel="stylesheet" href="./css/style.css">

</head>
<?php
include __DIR__ . '/../Header/index.php';
?>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-sm-4 pt-2">
                    <form class="form-feedback" novalidate id="captcha_form">
                        <p class="form-feedback-title">Форма обратной связи</p>
                        <div class="form-group">
                            <input type="text" name="email" class="form-control feedback-email" placeholder="Е-майл" required="required">
                            <div class="invalid-feedback">Введите валидный e-mail</div>
                        </div>
                        <div class="form-group">
                         <label for="feedback-text">Введите сообщение</label>   
                            <textarea class="form-control feedback-text" name="feedbackText" rows="4" required="required"></textarea>
                            <div class="invalid-feedback">Введите текст</div>
                        </div>
                        <div class="form-group">
                           <div class="form-check">
                            <input class="form-check-input" name="agreement" type="checkbox" id="checkbox">
                            <label class="form-check-label" for="checkbox">Согласен с условиями</label>
                           </div>           
                        </div>
                        <div class="form-group">
                         <label for="country">Выберите страну</label>  
                        <select id="country-feedback" class="form-select" name="country" aria-label="select">
                        <option value="0" selected>Россия</option>
                        <option value="1">Белоруссия</option>
                        <option value="2">Казахстан</option>
                        <option value="3">Узбекистан</option>
                        </select>
                        </div>
                        <div class="form-group">
                            <label>Выберите ваш пол</label>
                            <br>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" name="sexOption" id="man-opt" value="man" checked>
                                <label class="form-check-label" for="exampleRadios1">
                                  Мужчина
                                </label>
                              </div>
                              <div class="form-check-inline">
                                <input class="form-check-input" type="radio" name="sexOption" value="woman">
                                <label class="form-check-label" for="exampleRadios2">
                                  Женщина
                                </label>
                              </div>
                        </div>
                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="6LfsP-UiAAAAAH3MIC6tgDXcevKo7NGjOR4nOLON"></div>
                            <div class="invalid-feedback">Введите капчу</div>
                        </div>
                        <button class="btn-sm btn-secondary reset-btn">Сбросить</button>
                        <button id="btn-send" class="btn-sm btn-info">Отправить</button>
                    </form>
                        <div class="file-upload">             
                        <input id="file-input" name="fileupload"  class="form-control-file" type="file" id="customFile">                   
                        </div>
                </div>
            <div class="jumbotron col-sm-8 p-4 main-content">
                <h4>Описание</h4>
                <div>На сайте используется REST API с авторизацией по JWT-токену. Использовалась версия php 8.1</div>
                <h4>Установка базы данных</h4>
                <div>Для создания базы данных требуется создать базу данных
                    simtech. <br>
                    Далее нужно пройти по ссылке: <a href="/api/swagger">Сваггер</a> и выполнить GET запрос на адрес
                    `api/databaseinit/createTables` все таблицы буду сгенерированы автоматически. 
                    Можно также восстановить из дампа базы в папке ProjectSettings/db_backup.sql.
                    Настройки подключения к базе данных находятся в папке inc/config.php
                </div>
                <h4>Пароль администратора</h4>
                <div>Для просмотра отзывов, сформированных формой обратной связи требуется роль администратора<br>
                    Логин: admin<br>
                    Пароль: admin<br>
                </div>
                <h4>Настройки ngnix</h4>
                <div>После установки ngnix нужно заменить файл default который находится в папке /etc/ngnix/sites-available.
                    Измененный файл (default), который нужно заменить находится в папке ProjectSettings.
                    Также надо скопировать файл phpmyadmin.conf из папки ProjectSettings/phpmayadmin.conf в 
                    папку /etc/nginx/snippets/phpmyadmin.conf
                </div>
                <h4>Инструкция по установке CAPTCHA V2</h4> 
                <div> На данный момент приложение настроено для работы с доменом localhost.
                     Инструкция по настройке нового приложения находится в файле ProjectSettings/recaptcha.txt
                </div>
                <h4>Инструкция по настройке отправки сообщения на почтовый ящик администратора</h4> 
                <div> На данный момент приложение отключено для отправки e-mail сообщений. Для включения данной функции
                    нужно исправить в настройке inc/mailConfig.php<br>
                    define('IS_SEND_MAIL', true);<br>
                    Для проверки отправленных сообщений нужно зайти на почтовый сервис http://mail.ru со следующими авторизационными данными:<br>
                    Логин: simtech_test@mail.ru<br>
                    Пароль: EEituS+zbP32<br>
                     Инструкция по настройке нового почтового сервиса находится в файле ProjectSettings/mail.txt
                </div>
                <h4>Запуск юнит-тестов</h4>
                <div>Нужно выполнить запуск из корневой папки командой: vendor/bin/phpunit.
                     Для запуска e2e теста сайт должен быть настроен и запущен.
                </div>
            </div>
        </div>
    </div>
    <?php
    include __DIR__ . '/../Footer/index.html';
    ?>

</body>

</html>