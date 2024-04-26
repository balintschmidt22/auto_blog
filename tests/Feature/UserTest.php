<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_users_index_200(): void
    {
        $this->get('/users')->assertStatus(200);
    }

    public function test_users_add_moderator_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('/users/addModerator/' . $user->id)->assertStatus(403);
    }

    public function test_users_add_moderator_mod_403(): void
    {
        $user = User::where('role', 'mod')->first();
        $other = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('/users/addModerator/' . $other->id)->assertStatus(403);
    }

    public function test_users_add_moderator_adm_404(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('/users/addModerator/' . 20000)->assertStatus(404);
    }

    public function test_users_add_moderator_adm_302(): void
    {
        $user = User::where('role', 'adm')->first();
        $other = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('/users/addModerator/' . $other->id)->assertStatus(302);
    }

    public function test_users_remove_moderator_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();
        $other = User::where('role', 'mod')->first();

        $this->actingAs($user)->get('/users/removeModerator/' . $other->id)->assertStatus(403);
    }

    public function test_users_remove_moderator_mod_403(): void
    {
        $user = User::where('role', 'mod')->first();

        $this->actingAs($user)->get('/users/removeModerator/' . $user->id)->assertStatus(403);
    }

    public function test_users_remove_moderator_adm_404(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('/users/removeModerator/' . 20000)->assertStatus(404);
    }

    public function test_users_remove_moderator_adm_302(): void
    {
        $user = User::where('role', 'adm')->first();
        $other = User::where('role', 'mod')->first();

        $this->actingAs($user)->get('/users/removeModerator/' . $other->id)->assertStatus(302);
    }

    public function test_users_delete_user_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();
        $other = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('/users/delete/' . $other->id)->assertStatus(403);
    }

    public function test_users_delete_user_mod_403(): void
    {
        $user = User::where('role', 'mod')->first();
        $other = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('/users/delete/' . $other->id)->assertStatus(403);
    }

    public function test_users_delete_user_adm_404(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('/users/delete/' . 20000)->assertStatus(404);
    }

    public function test_users_delete_user_adm_302(): void
    {
        $user = User::where('role', 'adm')->first();
        $other = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('/users/delete/' . $other->id)->assertStatus(302);
    }

    public function test_users_change_password_usr_200(): void
    {
        $user = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('/users/changePassword/' . $user['id'])->assertStatus(200);
    }

    public function test_users_change_password_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();
        $other = User::where('role', 'mod')->first();

        $this->actingAs($user)->get('/users/changePassword/' . $other->id)->assertStatus(403);
    }

    public function test_users_message_guest(): void
    {
        $user = User::where('role', 'usr')->first();

        $this->get('/users/message/' . $user->id)->assertRedirect('login');
    }

    public function test_users_message_usr_404(): void
    {
        $user = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('/users/message/' . $user->id)->assertStatus(404);
    }

    public function test_users_message_usr_200(): void
    {
        $user = User::where('role', 'usr')->first();
        $other = User::where('role', 'mod')->first();

        $this->actingAs($user)->get('/users/message/' . $other->id)->assertStatus(200);
    }

    public function test_users_message_box_guest(): void
    {
        $this->get('/users/messageBox')->assertRedirect('login');
    }

    public function test_users_message_box_usr_200(): void
    {
        $user = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('/users/messageBox')->assertStatus(200);
    }

    public function test_users_show_200(): void
    {
        $user = User::first();
        $this->get('users/' . $user->id)->assertStatus(200);
    }

    public function test_users_show_404(): void
    {
        $this->get('/users/30000')->assertStatus(404);
    }

    public function test_users_user_edit_usr_200(): void
    {
        $user = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('users/' . $user->id . '/userEdit')->assertStatus(200);
    }

    public function test_users_user_edit_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();
        $other = User::where('role', 'mod')->first();

        $this->actingAs($user)->get('users/' . $other->id . '/userEdit')->assertStatus(403);
    }

    public function test_users_edit_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();
        $other = User::where('role', 'mod')->first();

        $this->actingAs($user)->get('users/' . $other->id . '/edit')->assertStatus(403);
    }

    public function test_users_edit_mod_403(): void
    {
        $user = User::where('role', 'mod')->first();
        $other = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('users/' . $other->id . '/edit')->assertStatus(403);
    }

    public function test_users_edit_adm_404(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('users/20000/edit')->assertStatus(404);
    }

    public function test_users_edit_adm_200(): void
    {
        $user = User::where('role', 'adm')->first();
        $other = User::where('role', 'usr')->first();

        $this->actingAs($user)->get('users/' . $other->id . '/edit')->assertStatus(200);
    }

    public function test_pdf_download(): void
    {
        $response = $this->get('users/pdf/download');

        $response->assertDownload('autoblog_users.pdf');
    }

    public function test_csv_download(): void
    {
        $response = $this->get('users/csv/download');

        $response->assertDownload('users.csv');
    }
}
