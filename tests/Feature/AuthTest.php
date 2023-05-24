<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase {

   use WithFaker;
   use RefreshDatabase;

   public const LOGIN_URL = '/api/login';
   public const LOGOUT_URL = '/api/logout';
   public const REGISTER_URL = '/api/register';

    public function test_user_cannot_register_if_he_not_provide_name_email_or_password() {

        $response = $this->post(self::REGISTER_URL,[],
            ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJson(fn (AssertableJson $json) =>
        $json->has('errors')
            ->where('errors.name.0', "The name field is required.")
            ->where('errors.email.0', "The email field is required.")
            ->where('errors.password.0', "The password field is required.")
            ->etc()
        );
    }
    public function test_registration_create_new_user() {
        $name = $this->faker->firstName . ' ' . $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $response = $this->post(self::REGISTER_URL,[
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

    public function test_cannot_create_user_with_same_email_as_another_user() {
        $name = $this->faker->firstName . ' ' . $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $this->post(self::REGISTER_URL,[
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ],['Accept' => 'application/json']);

        $response = $this->post(self::REGISTER_URL,[
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
    public function test_registered_account_can_be_logged_into() {
        $name = $this->faker->firstName . ' ' . $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $this->post(self::REGISTER_URL,[
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ],['Accept' => 'application/json']);

        $response = $this->post(self::LOGIN_URL,[
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
    public function test_login_with_wrong_credentials_is_rejected() {
        $name = $this->faker->firstName . ' ' . $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $this->post(self::REGISTER_URL,[
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
    public function test_you_can_logout_with_a_token() {
        $name = $this->faker->firstName . ' ' . $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $this->post(self::REGISTER_URL,[
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ],['Accept' => 'application/json']);

        $loginResponse = $this->post(self::LOGIN_URL,[
            'email' => $email,
            'password' => $password
        ],['Accept' => 'application/json']);

        $token = $loginResponse->json('token');

        $response = $this->post(self::LOGOUT_URL,[],[
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
