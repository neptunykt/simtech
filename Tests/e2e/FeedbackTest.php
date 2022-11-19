<?php
namespace Tests;
require_once __DIR__ . "./../../inc/captcha.php";
use PHPUnit\Framework\TestCase;
use GuzzleHttp;


class FeedbackTest extends TestCase {

    /**
     * End to End Unit тест на создание отзыва
    */
    public function testAddFeedback() {

        $http = new GuzzleHttp\Client;

        $response = $http->request("POST","http://localhost/api/feedback/addFeedback", [
            "form_params" => [
            "email" => "test@mail.ru",
            "isAgreed" => "on",
            "sexOption" => "man",
            "feedbackText" => "Привет",
            "test_mode_token" => TEST_MODE_KEY
        ]]);

        $this->assertEquals(204, $response->getStatusCode());
        
    }


}