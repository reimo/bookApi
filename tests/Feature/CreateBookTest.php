<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateBookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->post('/api/books', [
            "description" => "Even though i find a purple world yet i find a purple tears",
            "title" => "Happy Picure Sad People.",
            "author" => "Purple Violet",
            "amount" => 600
        ])
            ->assertStatus(201);
    }
}
