<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class TypeController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('types.create', [
            'brands' => Brand::all()->sortBy('name')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'brand' => ['required', 'string', 'exists:brands,name'],
                'name' => ['required', 'string', 'unique:types,type'],
            ]
        );

        $brandId = Brand::where('name', $data['brand'])->get()->first()['id'];

        $type = new Type;
        $type->type = $data['name'];

        $type->brand()->associate(
            $brandId
        );

        $type->save();

        Session::flash('type_added', $type);

        return Redirect::route('types.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('gallery.index', [
            $type = Type::findOrFail($id),
            'type' => $type,
            'brand' => Brand::find($type['brand_id']),
            $imgs = $type->images(),
            'image_count' => count($imgs->get()->toArray()),
            'images' => $imgs->with(['type', 'user'])->orderBy('created_at', 'DESC')->paginate(12),
            'title' => 'type'
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
