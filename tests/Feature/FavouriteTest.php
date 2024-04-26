<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class FavouriteTest extends TestCase
{
    public function test_favourites_guest(): void
    {
        $this->get('favourites')->assertRedirect('login');
    }
    public function test_favourites_usr_200(): void
    {
        $user = User::where('role', 'usr')->first();
        $this->actingAs($user)->get('favourites')->assertStatus(200);
    }
}
