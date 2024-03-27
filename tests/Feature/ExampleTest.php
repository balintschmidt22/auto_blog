<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
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

    public function test_uploading_with_authentication(): void
    {
        $user = User::where('role', 'usr')->first();

        $response = $this->actingAs($user)
            ->withSession(['username' => $user->username])
            ->get('/');

        $response->assertStatus(200);
    }

    public function pdf_download_test(): void
    {
        $response = $this->get('users/pdf/download');

        $response->assertDownload('users.pdf');
    }

    public function csv_download_test(): void
    {
        $response = $this->get('users/csv/download');

        $response->assertDownload('users.csv');
    }

    public function test_404_1(): void
    {
        $this->get('/users/10000')->assertStatus(404);
    }

    public function test_403_2(): void
    {
        $this->get('/types/create')->assertStatus(403);
    }

    public function test_403_3(): void
    {
        $this->get('/users/delete/3')->assertStatus(403);
    }

    public function test_403_4(): void
    {
        $this->get('/gallery/delete/3')->assertStatus(403);
    }

    public function test_500_1(): void
    {
        $this->get('/gallery')->assertStatus(200);
    }
}
