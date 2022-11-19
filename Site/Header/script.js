window.addEventListener('load', _ => {

  // снимаем проверку при вводе
  $('.login-login').on('input', function () {
    $('.login-login').next().hide();
  });

  $('.login-password').on('input', function () {
    $('.login-password').next().hide();
  });

  // Проверка авторизации
  if (localStorage.getItem('simtech-token') !== null &&
    localStorage.getItem('simtech-userName') !== null
  ) {
    login(localStorage.getItem('simtech-userName'));
  }

  // Попытка пройти на авторизованный ресурс
  $('.feedbacks').click(function () {
    if (localStorage.getItem('simtech-token') != null) {
      $('.feedbacks').attr('href', `./../Feedbacks/index.php?token=${localStorage.getItem('simtech-token')}`);
    }
    else {
      $('.feedbacks').attr('href', './../Feedbacks/index.php');
    }
  });
  $('#btn-login').click(function (event) {
    event.preventDefault();
    if (validateLoginForm()) {
      $('.login-login').next().hide();
      $('.login-password').next().hide();
      const data = $('.form-login').serializeArray();
      $.ajax({
        url: '/api/auth/login/index.php',
        method: 'post',
        dataType: 'json',
        data: data,
        success: function (data) {
          // закрываем дропдаун форму логина
          closeLoginDropDownForm();
          // записываем в localStorage
          localStorage.setItem('simtech-token', data.token);
          localStorage.setItem('simtech-userName', data.userName);
          // приветствуем пользователя
          login(data.userName);
          // перегружаем страницу
          $(location).attr('href', '/Site/Home/index.php');
        },
        error: errorHandler
      });
    }
  });
  function login(userName) {
    let loginLink = $('#collapsibleNavbar > div > a.login');
    // Поприветствуем юзера
    loginLink.text(`Привет, ${userName}!`);
    loginLink.removeClass('login-active').addClass('login-hidden');
    $('.logout-hidden').removeClass('logout-hidden').addClass('logout-active');
  };

  function logout() {
    // чистим сессию на беке
    $.ajax({
      url: '/api/auth/logout/index.php',
      method: 'post',
      success: function () {
        localStorage.removeItem('simtech-token');
        localStorage.removeItem('simtech-userName');
        let loginLink = $('#collapsibleNavbar > div > a.login');
        loginLink.text('Войти');
        loginLink.removeClass('login-hidden').addClass('login-active');
        $('.logout-active').removeClass('logout-active').addClass('logout-hidden');
        // редирект на домашнюю страницу
       $(location).attr('href', '/Site/Home/index.php');
      },
      error: errorHandler
    });
  }

  function closeLoginDropDownForm() {
    $('.navbar-nav.dropdown.show').removeClass('show');
    $('.dropdown-menu.dropdown-menu-right.show').removeClass('show');
    $('.dropdown-toggle.login').attr('aria-expanded', 'false');
  };

  function validateLoginForm() {
    // Логин
    const login = $('.login-login').val();
    if (!$('.login-login').val()) {
      $('.login-login').next().show();
      return false;
    }
    // Пароль
    if (!$('.login-password').val()) {
      $('.login-password').next().show();
      return false
    }
    return true;
  };
  $('.log-out.logout-active').click(function () {
    logout();
  });

});