<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase {

   use WithFaker;
   use RefreshDatabase;


    public function test_that_registration_endpoint_creates_new_user() {

        $name = $this->faker->firstName . ' ' . $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $response = $this->post('/api/register',[
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ],['Accept' => 'application/json']);

        $response->assertStatus(201);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('user')
                ->where('user.name', $name)
                ->where('user.email', $email)
                ->has('user.id')
                ->has('token')
                ->etc()
        );
    }
    public function test_that_registration_user_with_the_same_email_is_rejected() {
        $name = $this->faker->firstName . ' ' . $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $this->post('/api/register',[
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ],['Accept' => 'application/json']);

        $response = $this->post('/api/register',[
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ],['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJson(fn (AssertableJson $json) =>
        $json->where('message', 'The email has already been taken.')
            ->where('errors.email.0', 'The email has already been taken.')
            ->etc()
        );
    }
    public function test_that_registered_account_can_be_logged_into() {
        $name = $this->faker->firstName . ' ' . $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $this->post('/api/register',[
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ],['Accept' => 'application/json']);

        $response = $this->post('/api/login',[
            'email' => $email,
            'password' => $password
        ],['Accept' => 'application/json']);

        $response->assertStatus(201);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('user')
                ->where('user.name', $name)
                ->where('user.email', $email)
                ->has('user.id')
                ->has('token')
                ->etc()
        );
    }
    public function test_that_login_with_wrong_credentials_is_rejected() {
        $name = $this->faker->firstName . ' ' . $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $this->post('/api/register',[
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ],['Accept' => 'application/json']);

        $response = $this->post('/api/login',[
            'email' => $email,
            'password' => 'wrong password'
        ],['Accept' => 'application/json']);

        $response->assertStatus(401);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'Bad creds')
                ->etc()
        );

        $response = $this->post('/api/login',[
            'email' => $this->faker->email,
            'password' => 'wrong password'
        ],['Accept' => 'application/json']);

        $response->assertStatus(401);
        $response->assertJson(fn (AssertableJson $json) =>
        $json->where('message', 'Bad creds')
            ->etc()
        );
    }
    public function test_that_you_can_logout_with_a_token() {
        $name = $this->faker->firstName . ' ' . $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $this->post('/api/register',[
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ],['Accept' => 'application/json']);

        $loginResponse = $this->post('/api/login',[
            'email' => $email,
            'password' => $password
        ],['Accept' => 'application/json']);

        $token = $loginResponse->json('token');

        $response = $this->post('/api/logout',[],[
            'Accept' => 'application/json'
            ,'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'Logged out')
                ->etc()
        );


    }


}
