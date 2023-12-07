<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\Image;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    // public function test_admin_is_first(): void
    // {
    //     $admin = User::id(1);
    //     $this->assertTrue($admin->isAdmin);
    // }

    // public function test_images_exist(): void
    // {
    //     $images = Image::all()->toArray();
    //     $this->assertTrue(count($images) > 0);
    // }
}
