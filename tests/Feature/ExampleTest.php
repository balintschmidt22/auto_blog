<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_fallback_if_random_url(): void
    {
        $response = $this->get('/asd');

        $response->assertStatus(200);
    }
}
