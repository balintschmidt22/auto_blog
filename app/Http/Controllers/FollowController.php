<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\User;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['followUser', 'followBrand', 'followedUsers', 'followedBrands']);
    }

    public function followUser(Request $request)
    {
        $user = Auth::user();

        $q = $request->all();
        $id = $q['params']['id'];

        if ($user['id'] == $id) {
            abort(404);
        }

        $follow = User::findOrFail($id);

        $follows = $user->follows()->get()->toArray();
        $f_ids = array_column($follows, 'id');

        if (!in_array($follow['id'], $f_ids)) {
            $user->follows()->sync($follow, false);
        } else {
            $user->follows()->detach($follow);
        }
    }

    public function followBrand(Request $request)
    {
        $user = Auth::user();

        $q = $request->all();
        $id = $q['params']['id'];

        $follow = Brand::findOrFail($id);

        $follows = $user->followedBrands()->get()->toArray();
        $f_ids = array_column($follows, 'id');

        if (!in_array($follow['id'], $f_ids)) {
            $user->followedBrands()->sync($follow, false);
        } else {
            $user->followedBrands()->detach($follow);
        }
    }

    public function followedUsers()
    {
        $user = Auth::user();

        $follows = $user->follows()->get();

        $imgs = Image::whereIn('user_id', $follows->pluck('id'));

        return view('gallery.index', [
            'images' => $imgs->with(['type', 'user'])->orderBy('created_at', 'DESC')->paginate(12),
            'title' => 'Followed Users'
        ]);
    }

    public function followedBrands()
    {
        $user = Auth::user();

        $follows = $user->followedBrands()->get();
        $ids = [];
        foreach ($follows as $f) {
            $types = $f->types()->get();
            foreach ($types as $t) {
                array_push($ids, $t['id']);
            }
        }

        $imgs = Image::whereIn('type_id', $ids);

        return view('gallery.index', [
            'images' => $imgs->with(['type', 'user'])->orderBy('created_at', 'DESC')->paginate(12),
            'title' => 'Followed Brands'
        ]);
    }
}
