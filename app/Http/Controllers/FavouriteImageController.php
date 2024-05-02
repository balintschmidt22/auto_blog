<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteImageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('gallery.index', [
            $imgs = Auth::user()->likedImages(),
            'images' => $imgs->with(['type', 'user'])->orderBy('created_at', 'DESC')->paginate(12),
            'title' => 'Favourite Images'
        ]);
    }

    public function add(Request $request)
    {
        $q = $request->all();
        $id = $q['params']['id'];

        $image = Image::findOrFail($id);

        $user = Auth::user();

        $favs = $user->likedImages()->get()->toArray();
        $fav_ids = array_column($favs, 'id');

        if (!in_array($image['id'], $fav_ids)) {
            $user->likedImages()->sync($image, false);
        } else {
            $user->likedImages()->detach($image);
        }

        $likeCount = count($image->likedBy()->get());

        $imageUser = $image->user()->first();
        $imgs = User::findOrFail($imageUser['id'])->ownImages();
        $allLikes = 0;
        foreach ($imgs->get() as $i) {
            $allLikes += count($i->likedBy()->get()->toArray());
        }

        return response()->json([
            'likeCount' => $likeCount,
            'allLikes' => $allLikes
        ]);
    }
}
