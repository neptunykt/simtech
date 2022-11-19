 <!-- Навбар -->
 <script type="text/javascript" src="./../Shared/script.js"></script>
 <nav class="navbar navbar-expand-sm bg-dark navbar-dark px-5">
    <a class="navbar-brand" href="#">Компания</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="collapsibleNavbar">
        <!-- Ссылки -->
        <div class="navbar-nav">
            <a class="nav-link" href="/Site/Home/index.php">Домашняя</a>
            <a class="nav-link feedbacks" href="#">Отзывы</a>
        </div>
        <!-- Раскрывающаяся форма -->
        <div class="navbar-nav dropdown">
            <div><a href="#" data-toggle="dropdown" class="log-out logout-hidden">Выйти</a></div>
            <a href="#" data-toggle="dropdown" class="dropdown-toggle login login-active">Войти</a>
            <div class="dropdown-menu dropdown-menu-right">
                <form class="form-login" action="/api/auth/login/index.php" method="post">
                    <p class="form-title">Введите логин и пароль</p>
                    <div class="form-group">
                        <input type="text" class="form-control login-login" name="userName" placeholder="Логин" required="required">
                        <div class="invalid-feedback">Введите логин</div>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control login-password" placeholder="Пароль"
                            required="required">
                        <div class="invalid-feedback">Введите пароль</div>
                    </div>
                    <input id="btn-login" class="btn btn-primary btn-block" value="Войти">
                </form>
            </div>
        </div>
    </div>
</nav>
<?php
include __DIR__ . '/../Shared/modal.html';
?>
