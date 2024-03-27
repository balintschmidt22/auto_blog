<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    public function admin_exists(): void
    {
        $this->assertDatabaseHas('users', ['role' => 'adm']);
    }

    public function moderator_exists(): void
    {
        $this->assertDatabaseHas('users', ['role' => 'mod']);
    }

    public function user_exists(): void
    {
        $this->assertDatabaseHas('users', ['role' => 'usr']);
    }
}
