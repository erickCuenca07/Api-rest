<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

use function PHPUnit\Framework\assertTrue;

class UserTest extends TestCase
{
    use RefreshDatabase;
    // login
    public function testLogin()
    {
        $data = [
        "email"=>"test@gmail.com",
        "password"=>"12345678",
        ];

    $response = $this->post('http://localhost:8000/api/login',$data);

    $response
        ->assertStatus($response->getStatusCode())
        ->assertJsonStructure([
            'access_token', 'token_type', 'expires_in'
        ]);
    
    }
    // logout
    public function testLogout()
    {
    $data = [
        "email"=>"test@gmail.com",
        "password"=>"12345678",
        ];
    $token = JWTAuth::fromUser($data);

    $response = $this->post('http://localhost:8000/api/logout'.$token ,$data);

    $response
        ->assertStatus($response->getStatusCode())
        ->assertExactJson([
            'message' => 'Successfully logged out'
        ]);
    }   
    //create user
    public function test_create_user()
    {
        $data = ["user" => "creando",
        "name"=>"desde test",
        "surname"=>"eso mismo",
        "email"=>"test@gmail.com",
        "password"=>"12345678",
        "rol"=>"2"];

        $response = $this->post('http://localhost:8000/api/register',$data);

        $response
            ->assertStatus($response->getStatusCode())
            ->assertDatabaseHas('users', $data);
        
    }
    //delete
    public function test_delete_user()
    {
        $response = $this->delete('http://localhost:8000/api/users/1');

        $response
            ->assertStatus($response->getStatusCode())
            ->assertJson(["msg"=>"Usuario eliminado correctamente"]);
    }
    //put
    public function test_put_user()
    {
        $response = $this->put('http://localhost:8000/api/users/1',[
            "name" => "editando desde test"
        ]);

        $response
            ->assertStatus($response->getStatusCode())
            ->assertJson(["msg"=>"Usuario editado correctamente"]);
    }
}
