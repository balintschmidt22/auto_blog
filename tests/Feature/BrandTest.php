<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BrandTest extends TestCase
{
    public function test_brands_index_200(): void
    {
        $this->get('/brands')->assertStatus(200);
    }

    // public function test_brands_store_200(): void
    // {
    //     $user = User::factory()->create();
    //     $user->role = "adm";

    //     $response = $this->actingAs($user)
    //         ->post('/brands/store');

    //     $response->assertStatus(200);
    // }

    public function test_brands_create_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('/brands/create')->assertStatus(403);
    }

    public function test_brands_create_mod_200(): void
    {
        $user = User::where('role', 'mod')->first();

        $this->actingAs($user)->get('/brands/create')->assertStatus(200);
    }

    public function test_brands_create_adm_200(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('/brands/create')->assertStatus(200);
    }

    public function test_brands_delete_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();
        $brand = Brand::first();

        $this->actingAs($user)->get('/brands/delete/' . $brand->id)->assertStatus(403);
    }

    public function test_brands_delete_mod_403(): void
    {
        $user = User::where('role', 'mod')->first();
        $brand = Brand::first();

        $this->actingAs($user)->get('/brands/delete/' . $brand->id)->assertStatus(403);
    }

    public function test_brands_delete_adm_200(): void
    {
        $user = User::where('role', 'adm')->first();
        $brand = Brand::first();

        $this->actingAs($user)->get('/brands/delete/' . $brand->id)->assertStatus(302);
    }

    public function test_brands_delete_adm_404(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('/brands/delete/30000')->assertStatus(404);
    }

    // public function test_brands_search_usr_200(): void
    // {
    //     $user = User::factory()->create();

    //     $this->actingAs($user)->post('/brands/search', ['search' => 'a'], [])->assertStatus(200);
    // }

    public function test_brands_show_200(): void
    {
        $brand = Brand::first();
        $this->get('/brands/' . $brand->id)->assertStatus(200);
    }

    public function test_brands_show_404(): void
    {
        $this->get('/brands/30000')->assertStatus(404);
    }

    //TODO: Brands update

    public function test_brands_edit_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();
        $brand = Brand::first();

        $this->actingAs($user)->get('/brands/' . $brand->id . '/edit')->assertStatus(403);
    }

    public function test_brands_edit_mod_200(): void
    {
        $user = User::where('role', 'mod')->first();
        $brand = Brand::first();

        $this->actingAs($user)->get('/brands/' . $brand->id . '/edit')->assertStatus(200);
    }

    public function test_brands_edit_adm_200(): void
    {
        $user = User::where('role', 'adm')->first();
        $brand = Brand::first();

        $this->actingAs($user)->get('/brands/' . $brand->id . '/edit')->assertStatus(200);
    }

    public function test_brands_edit_adm_404(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('/brands/30000/edit')->assertStatus(404);
    }
}
