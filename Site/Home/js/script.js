window.addEventListener('load', _ => {
  // загрузка файла
  $(".custom-file-input").on("change", function () {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });
  // валидация е-майла по регулярному выражению
  function validateEmail(email) {
    return email.match(
      /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
  };
  
  function validateFeedbackForm() {
    // Это емайл
    const email = $(".feedback-email").val();
    if(grecaptcha.getResponse().length == 0) {
      // показываем ошибку и потом прячем через 1 сек
      $(".g-recaptcha").next().show().delay(1000).fadeOut();
      return false;
    }
    if (validateEmail(email) === null) {
      $(".feedback-email").next().show();
      return false;
    }
    // Текстовое сообщение
    if (!$(".feedback-text").val()) {
      $(".feedback-text").next().show();
      return false;
    }
    return true;
  }

  // снимаем проверку при вводе
  $(".feedback-email").on("input", function () {
    $(".feedback-email").next().hide();
  });

  $(".feedback-text").on("input", function () {
    $(".feedback-text").next().hide();
  });

  // отправка фидбека
  $("#btn-send").click(function (event) {
    event.preventDefault();
    //  если нет файлов, то отправляем без файла
    if (validateFeedbackForm()) {
      $(".feedback-email").next().hide();
      $(".feedback-text").next().hide();
      $(".g-recaptcha").next().hide();
      const data = $(".form-feedback").serializeArray();
      const captcha = grecaptcha.getResponse();
      data.push({ name: "g-captcha-response", value: captcha });
      if ($('#file-input')[0].files[0] === undefined) {
        $.ajax({
          url: '/api/feedback/addFeedback/index.php',
          method: 'post',
          data: data,
          success: function () {
            $("reset-btn").click();
            $(".modal-body > p").text("Ваш отзыв успешно сохранен!");
            $("#modalPopup").show();
            // очищаем форму
            resetForm();
          },
          error: errorHandler
        });
        // иначе сперва отправляем файл
      } else {
        let formData = new FormData();
        formData.append("file", $('#file-input')[0].files[0]);
        fetch("/api/file/upload/index.php", { method: "POST", body: formData })
          .then((response) => {
            if (response.ok) {
              return response.json();
            }
            throw response.json();
          })
          .then(feedBackId => {
            // выполняем update
            data.push({ name: 'id', value: feedBackId });
            $.ajax({
              url: '/api/feedback/updateFeedback/index.php',
              method: 'post',
              data: data,
              success: function () {
                resetForm();
                $(".modal-body > p").text("Ваш отзыв успешно сохранен!");
                $("#modalPopup").show();
              },
              error: errorHandler
            });
          })
          .catch(err => err.then(e => {
            $(".modal-body > p").text(e.error);
            $("#modalPopup").show();
          }))
      }
    }
  });

// Сброс нажатие очистка загруженного файла
$("reset-btn").click(function () {
  resetForm();
})
function resetForm() {
  $("#file-input")[0].value = null;
  $("input.feedback-email").val("");
  $("textarea.feedback-text").val("");
  $("#checkbox").prop("checked", false);
  $("#man-opt").prop("checked", "man");
  // сброс капчи
  grecaptcha.reset();
}
});



