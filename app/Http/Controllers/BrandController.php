<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin')->only(['create', 'store']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('brands.index', [
            'brands' => Brand::all()->sortBy('name')->toArray(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'unique:brands,name'],
                'country' => ['required', 'string'],
                'image' => ['required', 'file', 'image', 'max: 4096'],
            ]
        );

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $img = $file->store('brands', ['disk' => 'public']);
        }

        $brand = new Brand;
        $brand->name = $data['name'];
        $brand->country = $data['country'];
        $brand->image = $img;

        $brand->save();

        Session::flash('brand_added', $brand);

        return Redirect::route('brands.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::findOrFail($id);
        $likedBy = 0;
        foreach ($brand->types()->get() as $t) {
            foreach ($t->images()->get() as $i) {
                $likedBy += count($i->likedBy()->get()->toArray());
            }
            ;
        }

        return view('brands.show', [
            'brand' => $brand,
            'types' => $brand->types()->get()->toArray(),
            'followedBy' => count($brand->followedBy()->get()),
            'likedBy' => $likedBy,
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
    public function destroy(string $id)
    {
        //
    }
}
