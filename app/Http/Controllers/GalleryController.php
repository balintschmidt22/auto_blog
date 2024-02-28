<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Image;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "verified"])->only(['create', 'store', 'gettypes']);
        $this->middleware(["can:admin"])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('gallery.index', [
            'images' => Image::with(['type', 'user'])->orderBy('created_at', 'DESC')->paginate(12),
            //'image_count' => count(Image::all()->toArray()),
            'title' => 'Gallery'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gallery.create', [
            'brands' => Brand::all()->sortBy('name'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'image' => ['required', 'file', 'image', 'max: 4096'],
                'location' => ['required', 'string'],
                'brand' => ['required', 'string', 'exists:brands,name'],
                'type' => ['required', 'integer', 'exists:types,id']
            ]
        );

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $img = $file->store('images', ['disk' => 'public']);
        }

        $image = new Image;
        $image->image = $img;
        $image->location = $data['location'];
        $image->type()->associate(
            $data['type']
        );
        $image->user()->associate(
            Auth::id()
        );

        $image->save();

        Session::flash('image_uploaded', $image);

        return Redirect::route('gallery.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('gallery.show', [
            $image = Image::findOrFail($id),
            $type = $image->type(),
            'type' => $type->get()->first(),
            'brand' => Brand::find($type->get()->first()['brand_id']),
            'image' => $image,
            $likes = $image->likedBy(),
            'like_count' => count($likes->get()->toArray()),
            'likes' => $likes,
            'comments' => $image->comments()->orderBy('created_at', 'ASC')->get()->toArray(),
        ]);
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
    public function delete(string $id)
    {
        $image = Image::findOrFail($id);
        $image->delete();

        Session::flash('image_deleted', $image);

        return redirect('/gallery');
    }

    public function gettypes(Request $request)
    {
        $selectedBrand = $request->query('brand');

        $brandId = Brand::where('name', $selectedBrand)->get()->first()['id'];

        $types = Type::orderBy('type')->where('brand_id', $brandId)->get()->toArray(); //pluck('type', 'id');

        return $types;
    }
}
