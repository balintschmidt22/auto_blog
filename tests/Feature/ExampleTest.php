<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use SebastianBergmann\Type\VoidType;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_404_1(): void
    {
        $this->get('/users/10000')->assertStatus(404);
    }

    public function test_404_2(): void
    {
        $this->get('/brands/3000')->assertStatus(404);
    }

    public function test_403_1(): void
    {
        $this->get('/brands/create')->assertStatus(403);
    }
}
