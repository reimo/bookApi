<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Http\Response;

class CreateUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_createUser()
    {
        $this->post('/api/users', [
            "name" =>  "puwrple tewars",
            "password" => "123456",
            "email" => "purpwle@teasrs.com",
        ])
            ->assertStatus(201);
    }
}
