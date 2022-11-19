<?php
namespace Controller;

use App\DAL\FeedbackService;
use Controller\BaseController;
use App\Models\ErrorResponseModel;
use App\Services\CaptchaService;
use App\Services\MailService;
use \Exception;

define("MAX_TEXT_LENGTH",255);


class FeedbackController extends BaseController
{
    /**
     * Метод добавления одного фидбека
    */
    public function addFeedbackAction() {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $feedback = $this->validateAndFillFeedback($requestMethod);
        if(empty($_POST["g-captcha-response"]) && empty($_POST["test_mode_token"])) {
            $this->sendErrorField("Требуется CAPTCHA");
        }
        else if(!empty($_POST["g-captcha-response"])){
            $captchaService = new CaptchaService();
            if(!$captchaService->checkCaptcha($_POST["g-captcha-response"])) {
               $this->sendErrorField("Неправильно введен код капчи");
            }
        // в тестовом режиме на почту не отправляем
        // тут сперва может просто файл прийти
        $mailService = new MailService();
        try {
        $mailService->sendEmail($feedback);
        }
        catch(Exception $ex) {
            $this->sendErrorField("Ошибка отправки почты" . $ex->getMessage()); 
        }
       
        }
        else if(!empty($_POST["test_mode_token"])) {
            $captchaService = new CaptchaService();
            if(!$captchaService->checkTestMode($_POST["test_mode_token"])) {
                $this->sendErrorField("Неправильный код проверки тестирования");
            }
        }
        $feedbackService = new FeedbackService();
        $feedbackService->addFeedback($feedback);
        $this->sendOk();
       
    }


    /**
     * Метод обновления одного фидбека
     */
    public function updateFeedbackAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $feedback = $this->validateAndFillFeedback($requestMethod, true);
        $feedbackService = new FeedbackService();
        if(empty($_POST["g-captcha-response"]) && empty($_POST["test_mode_token"])) {
            $this->sendErrorField("Требуется CAPTCHA");
        }
        else if(!empty($_POST["g-captcha-response"])){
            $captchaService = new CaptchaService();
            if(!$captchaService->checkCaptcha($_POST["g-captcha-response"])) {
               $this->sendErrorField("Неправильно введен код капчи");
            }
        // в тестовом режиме на почту не отправляем
        $mailService = new MailService();
        try {
        // достаем имя файла
        $feedbackOld = $feedbackService->getFeedbackById($feedback["id"]);
        $mailService->sendEmail($feedback,$feedbackOld[0]["FileName"]);
        }
        catch(Exception $ex) {
            $this->sendErrorField("Ошибка отправки почты: " . $ex->getMessage()); 
        }
        }
        else {
            $captchaService = new CaptchaService();
        if($captchaService->checkCaptcha($_POST["g-captchа-response"])) {
           $this->sendErrorField("Неправильно введен код капчи");
        }
        }
      
        $feedbackService->updateFeedback($feedback);
        $this->sendOk();
        
    }
    /**
     * Метод для проверки длины текста
     * @param mixed $text
     * @param mixed $fieldName
     */
    function checkTextLength($text, $fieldName) {
        $fieldFrontTextName = "";
        switch($fieldName) {
            case "feedbackText":
                $fieldFrontTextName = " текстового поля отзыва";
                break;
            case "email":
                $fieldFrontTextName =" поля ввода e-mail";
                break;
            default:
                break;
        }

        if(strlen($text)>255){
           $this->sendErrorField("Длина " . $fieldFrontTextName . " превышает 255 символов");
        }       
    }

    /**
     * Проверка заполнения
     * текстовых полей
     */
    function sendErrorField($errorText) {
        $errorResponse = new ErrorResponseModel();
        $errorResponse->description = $errorText;
        $errorResponse->header = "HTTP/1.1 400 Bad request";
        $this->sendError($errorResponse);
        exit;
    }

    /**
     * Валидация и заполнение фидбека
     * @param mixed $requestMethod
     * @return array
     */
    function validateAndFillFeedback($requestMethod, $checkId = false) {
        if (strtoupper($requestMethod) == "POST") {
            try {
                $feedback = [];
                if($checkId) {
                    $feedback["id"] = trim($_POST["id"]);
                    if(empty($feedback["id"])) {
                       $this->sendErrorField("Не заполнено поле id");
                   }
                }
                $postField = trim($_POST["email"]);
                if(empty($postField)) {
                    $this->sendErrorField("Не заполнено поле e-mail");
               }
               if(!filter_var($postField, FILTER_VALIDATE_EMAIL)){
                $this->sendErrorField($postField . "Невалидный e-mail");
               }
                $feedback["email"] = $postField;
                if(empty($_POST["agreement"])){
                    $feedback["isAgreed"] = 0; 
                }
                else {
                $agreement = trim($_POST["agreement"]);
                $feedback["isAgreed"] = ($agreement == "on")? 1 : 0;
                }
                $sexOption = trim($_POST["sexOption"]);
                if(empty($sexOption)) {
                    $this->sendErrorField("Не заполнено поле пол");
               }
                $feedback["sexOption"] = ($sexOption == "man")? 1 : 0;
               
                $feedback["feedbackText"] = trim($_POST["feedbackText"]);
                if(empty($feedback["feedbackText"])) {
                    $this->sendErrorField("Не заполнено текстовое поле");
                }
                $this->checkTextLength($feedback["email"], "email");
                $this->checkTextLength($feedback["feedbackText"], "feedbackText");
               return $feedback;
            }
            catch(Exception $e) {
                throw new Exception($e->getMessage());
            }       
    }
    $this->sendErrorField("Method not found");
    }

}
