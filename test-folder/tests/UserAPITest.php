<?php

use PHPUnit\Framework\TestCase;

class UserApiTest extends TestCase
{
    private $baseUrl = "http://localhost"; 

    public function testGet_UserList(): void {
        $ch = curl_init("{$this->baseUrl}/signup.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(200, $status);
    }

    public function testPost_CreateUser(): void {
        $data = json_encode([
            'username' => 'testuser_' . rand(1000, 9999),
            'psw' => 'testpassword'
        ]);

        $ch = curl_init("{$this->baseUrl}/signup.php/user/create");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(201, $status);
    }

    public function testPost_LoginUser(): void {
        $data = json_encode([
            'username' => 'diamond', // use an existing user
            'psw' => '1234567890'
        ]);

        $ch = curl_init("{$this->baseUrl}/login.php/user/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(201, $status);
    }

    public function testPost_FailedLogin(): void {
        $data = json_encode([
            'username' => 'not_valid_user',
            'psw' => 'wrong_password'
        ]);

        $ch = curl_init("{$this->baseUrl}/login.php/user/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(201, $status);
    }

    public function testPost_CreateListing(): void {
        $data = json_encode([
            'listing_name' => 'Test Listing',
            'listing_descript' => 'This is a test listing.',
            'price' => 150,
            'username' => 'diamond' 
        ]);
    
        $ch = curl_init("{$this->baseUrl}/newlisting.php/listing/create");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        $this->assertEquals(201, $status);
    }
    
}
