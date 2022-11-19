function errorHandler(err) {
    // сброс капчи если есть
   if(typeof grecaptcha !== "undefined") {
        grecaptcha.reset();
    }
    let result = "";
    if (err.responseJSON?.error != undefined) {
        result = err.responseJSON?.error;
    }
    else {
        result = err.message;
    }
    $(".modal-body > p").text(result);
    $("#modalPopup").show();
}

window.addEventListener('load', _ => {
// прячем модальное окно
$("div.modal-footer > button").click(function () {
    $("#modalPopup").hide();
  });
}
);