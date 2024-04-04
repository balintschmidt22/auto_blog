<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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

        return $likeCount;
    }
}
