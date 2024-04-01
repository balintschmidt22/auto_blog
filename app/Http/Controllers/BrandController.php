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
        $this->middleware('can:moderator')->only(['create', 'edit', 'update', 'store']);
        $this->middleware('can:admin')->only(['delete']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('brands.search', [
            'brands' => Brand::all()->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->toArray(),
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
            'types' => $brand->types()->get()->sortBy('type', SORT_NATURAL | SORT_FLAG_CASE)->toArray(),
            'followedBy' => count($brand->followedBy()->get()),
            'likedBy' => $likedBy,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('brands.edit', [
            'brand' => Brand::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'unique:brands,name,' . $id],
                'country' => ['required', 'string'],
                'image' => ['file', 'image', 'max: 4096'],
            ]
        );
        $brand = Brand::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($brand['image'] !== null) {
                if (str_starts_with($brand['image'], "https"))
                    $brand['image'] = "";
                else {
                    unlink(public_path() . "/storage/" . $brand['image']);
                }
            }

            $file = $data['image'];

            $image = $file->store('images', ['disk' => 'public']);

            $brand->image = $image;
        }

        $brand->update(['name' => $data['name'], 'country' => $data['country']]);

        if ($brand->wasChanged()) {
            Session::flash('brand_edited', $brand);
        }


        return redirect('brands/' . $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        Session::flash('brand_deleted', $brand);

        return redirect('brands');
    }

    public function search(Request $request)
    {
        $q = $request->all();
        $query = $q['params']['search'];

        $brands = Brand::all();
        if (trim($query) !== "") {
            $filteredBrands = $brands->filter(function ($item) use ($query) {
                return str_contains(strtolower($item['name']), strtolower($query)) !== false;
            })->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->values();
        } else {
            $filteredBrands = [];
        }

        return $filteredBrands;
    }
}
