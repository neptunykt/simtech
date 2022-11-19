<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once(PROJECT_ROOT_PATH . "inc/mailConfig.php");

class MailService
{
    /**
     * Функция отправки почты
     * @param mixed $data
     * @param mixed $fileName
     */
    public function sendEmail($data, $fileName = null)
    {
        if (IS_SEND_MAIL) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = SMTP_SERVER;
                $mail->SMTPAuth   = true;
                $mail->Username   = MAIL_USER_NAME;
                $mail->Password   = MAIL_PASSWORD;
                if (ENCRYPTION_TYPE == "SSL") {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                } else if (ENCRYPTION_TYPE == "TLS") {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                }
                $mail->Port = 465;
                $mail->CharSet = 'UTF-8';

                $mail->setFrom("simtech_test@mail.ru", "Admin");
                $mail->addAddress("simtech_test@mail.ru", "Admin");
                if (!empty($fileName)) {
                    $mail->addAttachment(PROJECT_ROOT_PATH . "Site/uploads/" . $fileName);
                }
                $mail->isHTML(true);
                $mail->Subject = "Поступила заявка из обратной формы";
                $mail->Body = "<p>Адрес e-mail заявителя: " . $data["email"] . "</p><p>Согласие с условиями: " .
                    (($data["isAgreed"] == 1) ? "Да" : "Нет") . "</p><p>Пол заявителя: " . (($data["sexOption"] == 1) ? "мужской" : "женский") . "</p>" .
                    "<p>Текст обратной формы: " . $data["feedbackText"] . "</p>";
                $mail->send();
            } catch (Exception $ex) {
                throw $ex;
            }
        }
    }
}
