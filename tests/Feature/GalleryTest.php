<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\User;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    public function test_gallery_index_200(): void
    {
        $this->get('/gallery')->assertStatus(200);
    }

    public function test_gallery_create_guest(): void
    {
        $this->get('/gallery/create')->assertRedirect('login');
    }

    public function test_gallery_create_usr_unverified(): void
    {
        $user = User::factory()->create();
        $user->email_verified_at = null;
        $user->save();

        $this->actingAs($user)->get('/gallery/create')->assertRedirect('/email/verify');
    }

    public function test_gallery_create_usr_200(): void
    {
        $user = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('/gallery/create')->assertStatus(200);
    }

    public function test_gallery_create_mod_200(): void
    {
        $user = User::where('role', 'mod')->first();

        $this->actingAs($user)->get('/gallery/create')->assertStatus(200);
    }

    public function test_gallery_create_adm_200(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('/gallery/create')->assertStatus(200);
    }

    public function test_gallery_delete_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();
        $image = Image::first();

        $this->actingAs($user)->get('/gallery/delete/' . $image->id)->assertStatus(403);
    }

    public function test_gallery_delete_mod_403(): void
    {
        $user = User::where('role', 'mod')->first();
        $image = Image::first();

        $this->actingAs($user)->get('/gallery/delete/' . $image->id)->assertStatus(403);
    }

    public function test_gallery_delete_adm_200(): void
    {
        $user = User::where('role', 'adm')->first();
        $image = Image::first();

        $this->actingAs($user)->get('/gallery/delete/' . $image->id)->assertStatus(302);
    }

    public function test_gallery_delete_adm_404(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('/gallery/delete/30000')->assertStatus(404);
    }

    public function test_gallery_show_200(): void
    {
        $image = Image::first();
        $this->get('/gallery/' . $image->id)->assertStatus(200);
    }

    public function test_gallery_show_404(): void
    {
        $this->get('/gallery/30000')->assertStatus(404);
    }

    public function test_gallery_edit_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();
        $image = Image::first();

        $this->actingAs($user)->get('/gallery/' . $image->id . '/edit')->assertStatus(403);
    }

    public function test_gallery_edit_mod_200(): void
    {
        $user = User::where('role', 'mod')->first();
        $image = Image::first();

        $this->actingAs($user)->get('/gallery/' . $image->id . '/edit')->assertStatus(200);
    }

    public function test_gallery_edit_adm_200(): void
    {
        $user = User::where('role', 'adm')->first();
        $image = Image::first();

        $this->actingAs($user)->get('/gallery/' . $image->id . '/edit')->assertStatus(200);
    }

    public function test_gallery_edit_adm_404(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('/gallery/30000/edit')->assertStatus(404);
    }
}
