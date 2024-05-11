<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class FollowTest extends TestCase
{
    public function test_followed_users_guest(): void
    {
        $this->get('follows/followedUsers')->assertRedirect('login');
    }
    public function test_followed_users_200(): void
    {
        $user = User::where('role', 'usr')->first();
        $this->actingAs($user)->get('followedUsers')->assertStatus(200);
    }
    public function test_followed_brands_guest(): void
    {
        $this->get('follows/followedBrands')->assertRedirect('login');
    }
    public function test_followed_brands_200(): void
    {
        $user = User::where('role', 'usr')->first();
        $this->actingAs($user)->get('followedBrands')->assertStatus(200);
    }
}
