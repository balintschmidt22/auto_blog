<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Message;
use Illuminate\Database\Seeder;
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
        $user_count = rand(14, 20);
        $users = User::factory($user_count)->create();

        $admin = User::find(1);
        $admin['username'] = 'admin';
        $admin['email'] = 'admin@autoblog.com';
        $admin['password'] = bcrypt('adminpwd');
        $admin['country'] = 'Hungary';
        $admin['profile_picture'] = "placeholders/Admin_Profile.png";
        $admin['role'] = "adm";
        $admin->save();

        $brand_count = rand(15, 25);
        $brands = Brand::factory($brand_count)->create();

        $type_count = $brand_count * rand(4, 11);
        $types = Type::factory($type_count)->create();

        $image_count = $user_count * rand(10, 20);
        $images = Image::factory($image_count)->create();

        $comment_count = $image_count * 2 + rand(1, 30);
        $comments = Comment::factory($comment_count)->create();

        $message_count = $user_count * 10 + rand(1, 10);
        $messages = Message::factory($message_count)->create();

        for ($i = 1; $i < 4; $i++) {
            $users[$i]['role'] = "mod";
            $users[$i]['profile_picture'] = "placeholders/Moderator_Profile.png";
            $users[$i]->save();
        }

        $types->each(function ($type) use (&$brands) {
            $type->brand()->associate(
                $brands->random()->id
            );
            $type->save();
        });

        $images->each(function ($image) use (&$types, &$users) {
            $type = $types->random();
            $image->type()->associate(
                $type->id
            );
            $image->user()->associate(
                $users->random()->id
            );
            $image->save();
        });

        $comments->each(function ($comment) use (&$users, &$images) {
            $comment->user()->associate(
                $users->random()->id
            );
            $comment->image()->associate(
                $images->random()->id
            );
            $comment->save();
        });

        $messages->each(function ($message) use (&$users, &$user_count) {
            $id = $users->random()->id;
            $message->from()->associate(
                $id
            );
            $message->to()->associate(
                $users->where('id', '!=', $id)->random()->id
            );
            $message->save();
        });

        $users->each(function ($user) use (&$images, &$image_count, &$brands, &$brand_count, &$users, &$user_count) {
            $user->likedImages()->sync(
                $images->random(
                    rand(0, ceil($image_count / 2))
                )
            );
            $user->followedBrands()->sync(
                $brands->random(
                    rand(0, ceil($brand_count / 2))
                )
            );
            $user->follows()->sync(
                $users->where('id', '!=', $user['id'])->random(
                    rand(0, ceil($user_count / 2))
                )
            );
        });
    }
}
