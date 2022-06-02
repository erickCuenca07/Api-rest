<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\Feature\Config;
use function PHPUnit\Framework\assertTrue;

class UserTest extends TestCase
{
    use RefreshDatabase;
    //metodo que me dara el token
    protected function authenticate(){
        $user = User::create([
            "user" => "creando",
            "name"=>"desde test",
            "surname"=>"eso mismo",
            "email"=>"test@gmail.com",
            "password"=>bcrypt("12345678"),
            "rol"=>"1"
        ]);
        $token = JWTAuth::fromUser($user);
        return $token;
    }
    //metodo que comprueba el login
    public function testLogin()
    {
        User::create(["user" => "creando",
            "name"=>"desde test",
            "surname"=>"eso mismo",
            "email"=>"test@gmail.com",
            "password"=>bcrypt("12345678"),
            "rol"=>"1"]);
        
            $response = $this->json('POST',route('api.login'),[
                'email'=>'test@gmail.com',
                'password'=>'12345678'
            ]);
        
            $response
            ->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
            
    }
    //registarse sin iniciar sesion 
    public function testRegister()
    {
        $data =["user" => "creando",
            "name"=>"desde test",
            "surname"=>"eso mismo",
            "email"=>"test@gmail.com",
            "password"=>"12345678",
            "rol"=>"1"];

            $response = $this->json('POST',route('api.register'),$data);
            $response
            ->assertStatus(201);       
    }
     // logout metodo que me cierra sesion
    public function testLogout()
    {
        $token = $this->authenticate();//llamo al metodo que da el token
        $response = $this->withHeaders([
        'Authorization' => 'Bearer '. $token,
        ]);
    $data = [
        "email"=>"test@gmail.com",
        "password"=>"12345678",
        ];

    $response = $this->json('POST',route('api.logout'),$data);

    $response->assertStatus(200);
    }   
    //me devulve el listado de usuarios
    public function testIndexusers()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
        'Authorization' => 'Bearer '. $token,
        ]);
    $data = [
        "email"=>"test@gmail.com",
        "password"=>"12345678"
        ];

    $response = $this->json('GET',route('api.getAll'),$data);

    $response->assertStatus(200);
    }
    //metodo que me devuelve un solo usuario 
    public function test_un_solo_user()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
        'Authorization' => 'Bearer '. $token,
        ]);
    $data = [
        "email"=>"test@gmail.com",
        "password"=>"12345678"
        ];

    $response = $this->json('GET','/api/users/1',$data);

    $response->assertStatus(200);
    }
    //metodo que crea usuarios una vez iniciada sesion
    public function test_create_user()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
        'Authorization' => 'Bearer '. $token,
        ]);
    $data = [
        "user" => "creando",
        "name"=>"desde test",
        "surname"=>"eso mismo",
        "email"=>"test@gmail.com",
        "password"=>"12345678",
        "rol"=>"2"
        ];

    $response = $this->json('POST',route('api.create'),$data);

    $response->assertStatus(200);
    }
    //metodo que me elimina un usuario
    public function test_delete_user()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
        'Authorization' => 'Bearer '. $token,
        ]);
        $response = $this->json('DELETE','/api/users/1');
        $response->assertStatus(200);
    }
    //metodo que me actualiza un usuario
    public function test_put_user()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
        'Authorization' => 'Bearer '. $token,
        ]);
        $response = $this->json('PUT','api/users/1',['user' => 'erick']);
        $response->assertStatus(200);
    }
}
