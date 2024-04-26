<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function test_comments_delete_usr_403(): void
    {
        $user = User::where('role', 'usr')->first();
        $comment = Comment::first();

        $this->actingAs($user)->get('/comments/delete/' . $comment->id)->assertStatus(403);
    }

    public function test_comments_delete_mod_200(): void
    {
        $user = User::where('role', 'mod')->first();
        $comment = Comment::first();

        $this->actingAs($user)->get('/comments/delete/' . $comment->id)->assertStatus(302);
    }

    public function test_comments_delete_adm_200(): void
    {
        $user = User::where('role', 'adm')->first();
        $comment = Comment::first();

        $this->actingAs($user)->get('/comments/delete/' . $comment->id)->assertStatus(302);
    }

    public function test_comments_delete_adm_404(): void
    {
        $user = User::where('role', 'adm')->first();

        $this->actingAs($user)->get('/comments/delete/30000')->assertStatus(404);
    }
}
