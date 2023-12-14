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

        if (!User::where('username', '=', 'admin')->first()) {
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
        $user_count = rand(14, 20);
        $users = User::factory($user_count)->create();

        $brand_count = rand(15, 25);
        $brands = Brand::factory($brand_count)->create();

        $type_count = $brand_count * rand(4, 11);
        $types = Type::factory($type_count)->create();

        $image_count = $user_count * rand(10, 20);
        $images = Image::factory($image_count)->create();

        $types->each(function ($type) use (&$brands) {
            $type->brand()->associate(
                $brands->random()->id
            );
            $type->save();
        });

        $images->each(function ($image) use (&$types) {
            $type = $types->random();
            $brand = $type->brand()->get()->first();
            $image->type()->associate(
                $type->id
            );
            $image['image'] = "https://live.staticflickr.com/3170/3115821566_7cd47f042f_c.jpg"; //fake()->imageUrl(400, 300, $brand->name, FALSE, $type->type, FALSE);
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
    }
}
