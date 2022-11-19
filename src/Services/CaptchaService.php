<?php
namespace App\Services;
require_once PROJECT_ROOT_PATH . "inc/captcha.php";

class CaptchaService {
    
    /**
     * Валидация капчи
     * @param mixed $recaptchaData
     * @return bool
     */
   public function checkCaptcha($recaptchaData) {
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . SECRET_KEY ."&response=" . 
        $recaptchaData);
        $responseData = json_decode($response);
        if(($responseData->success)) {
            return true;
        }
        return false;

    }

    /**
     * Проверка тестового режима (без капчи)
     * @param mixed $testModeKey
     * @return bool
     */
    public function checkTestMode($testModeKey) {
        return $testModeKey == TEST_MODE_KEY;
    }
}