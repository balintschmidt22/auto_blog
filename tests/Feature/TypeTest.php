<?php

namespace Tests\Feature;

use App\Models\Type;
use App\Models\User;
use Tests\TestCase;

class TypeTest extends TestCase
{
    public function test_types_create_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('types/create')->assertStatus(403);
    }

    public function test_types_create_mod_200(): void
    {
        $user = User::where('role', 'mod')->first();

        $this->actingAs($user)->get('types/create')->assertStatus(200);
    }

    public function test_types_create_adm_200(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('types/create')->assertStatus(200);
    }

    public function test_types_delete_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();
        $type = Type::first();

        $this->actingAs($user)->get('types/delete/' . $type->id)->assertStatus(403);
    }

    public function test_types_delete_mod_403(): void
    {
        $user = User::where('role', 'mod')->first();
        $type = Type::first();

        $this->actingAs($user)->get('types/delete/' . $type->id)->assertStatus(403);
    }

    public function test_types_delete_adm_200(): void
    {
        $user = User::where('role', 'adm')->first();
        $type = Type::first();

        $this->actingAs($user)->get('types/delete/' . $type->id)->assertStatus(302);
    }

    public function test_types_delete_adm_404(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('types/delete/30000')->assertStatus(404);
    }

    public function test_types_show_200(): void
    {
        $type = Type::first();
        $this->get('types/' . $type->id)->assertStatus(200);
    }

    public function test_types_show_404(): void
    {
        $this->get('types/30000')->assertStatus(404);
    }

    public function test_types_edit_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();
        $type = Type::first();

        $this->actingAs($user)->get('types/' . $type->id . '/edit')->assertStatus(403);
    }

    public function test_types_edit_mod_200(): void
    {
        $user = User::where('role', 'mod')->first();
        $type = Type::first();

        $this->actingAs($user)->get('types/' . $type->id . '/edit')->assertStatus(200);
    }

    public function test_types_edit_adm_200(): void
    {
        $user = User::where('role', 'adm')->first();
        $type = Type::first();

        $this->actingAs($user)->get('types/' . $type->id . '/edit')->assertStatus(200);
    }

    public function test_types_edit_adm_404(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('types/30000/edit')->assertStatus(404);
    }
}
