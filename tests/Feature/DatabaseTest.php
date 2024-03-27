<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    public function test_admin_exists(): void
    {
        $this->assertDatabaseHas('users', ['role' => 'adm']);
    }

    public function test_moderator_exists(): void
    {
        $this->assertDatabaseHas('users', ['role' => 'mod']);
    }

    public function test_user_exists(): void
    {
        $this->assertDatabaseHas('users', ['role' => 'usr']);
    }
}
