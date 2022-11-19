<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\DAL\FeedbackService;
define("PROJECT_ROOT_PATH", __DIR__ . "/../../");

class FeedbackRepositoryTest extends TestCase {

    private FeedbackService $feedbackService;

    protected function setUp() : void {

        $this->feedbackService = new FeedbackService();
        
    }

    public function testAddFeedbackRepository() {

        $expectedFeedback = [];
        $expectedFeedback["email"] = "test@mail.ru";
        $expectedFeedback["isAgreed"] = 1;
        $expectedFeedback["sexOption"] = 1; 
        $expectedFeedback["feedbackText"] = "Hello";
        $feedbackId = $this->feedbackService->addFeedback($expectedFeedback);
        $this->assertNotEmpty($feedbackId);
        $actualFeedback = $this->feedbackService->getFeedbackById($feedbackId);
        $this->assertEquals($expectedFeedback["email"],$actualFeedback[0]["Email"]);
        $this->assertEquals($expectedFeedback["feedbackText"],$actualFeedback[0]["Message"]);
        $this->assertEquals($expectedFeedback["isAgreed"],$actualFeedback[0]["IsAgreed"]);
        $this->assertEquals($expectedFeedback["sexOption"],$actualFeedback[0]["Sex"]);
        $this->feedbackService->deleteFeedbackById($feedbackId);
    }


}