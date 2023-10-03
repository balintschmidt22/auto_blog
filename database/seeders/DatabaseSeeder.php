<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Brand;
use App\Models\Type;
use App\Models\Image;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(10)->create();

        if (!User::where('name', '=', 'admin')->first()) {
            User::factory()->create([
                'username' => 'admin',
                'email' => 'admin@autoblog.com',
                'email_verified_at' => now(),
                'password' => bcrypt('adminpwd'),
                'country' => 'Hungary',
                'is_admin' => true,
                'remember_token' => Str::random(10),
            ]);
        }
        $user_count = rand(5, 9);
        $users = User::factory($user_count)->create();

        $brand_count = rand(8, 12);
        $brands = Brand::factory($brand_count)->create();

        $type_count = $brand_count * rand(3, 6);
        $types = Type::factory($type_count)->create();

        $image_count = $user_count * rand(8, 15);
        $images = Image::factory($image_count)->create();

        $types->each(function ($type) use (&$brands) {
            $type->brand()->associate(
                $brands->random()->id
            );
            $type->save();
        });

        $images->each(function ($image) use (&$types) {
            $image->type()->associate(
                $types->random()->id
            );
            $image->save();
        });

        $images->each(function ($image) use (&$users) {
            $image->user()->associate(
                $users->random()->id
            );
            $image->save();
        });

        $users->each(function ($user) use (&$images, &$image_count) {
            $user->likedImages()->sync(
                $images->random(
                    rand(0, $image_count)
                )
            );
        });

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}