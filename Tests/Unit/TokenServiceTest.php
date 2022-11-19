<?php
namespace Tests;
use PHPUnit\Framework\TestCase;
use App\Services\TokenService;


class TokenServiceTest extends TestCase {

    private TokenService $tokenService;
    private $user;

    protected function setUp() : void {

        $this->tokenService = new TokenService();
        $this->user = array();
        $this->user[] = [
            "UserName" => "user",
            "Password" => "password", 
            "RoleName" => "user",
            "Id" => 2
        ];
    }
     public function testTokenService(): void {

       $jwt = $this->tokenService->encode($this->user)["token"];
       $result = $this->tokenService->decode($jwt);
       $this->assertEquals("user", $result[0]);
    
    }
}


    
      