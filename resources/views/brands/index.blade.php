@extends('layouts.app')
@section('title', 'Brands')

@section('content')
<div class="container">
    <h1>Brands - {{count($brands)}}</h1>
    @auth
        @if(Auth::user()->isAdmin())
            <div>
                <a href="{{route('brands.create')}}" class="btn btn-primary mb-2">Add brand</a>
                <a href="{{route('types.create')}}" class="btn btn-primary mb-2">Add type</a>
            </div>
        @endif

        @if(Session::has('followed'))
            <div class="alert alert-success" role="alert">
                New brand followed: {{Session::get('followed')->name}}
            </div>
        @endif

        @if(Session::has('unfollowed'))
            <div class="alert alert-warning" role="alert">
                Brand is no longer followed: {{Session::get('unfollowed')->name}}
            </div>
        @endif

        @if(Session::has('brand_deleted'))
            <div class="alert alert-warning" role="alert">
                Brand deleted: {{Session::get('brand_deleted')->name}}
            </div>
        @endif
    @endauth
    <div>
        <table class="table table-bordered table-striped">
            <tr class="table-primary">
                @auth
                    <th>Follow</th>
                @endauth
                <th>Logo</th>
                <th>Name</th>
                <th>Country</th>
                <th>Types</th>
            </tr>
        @forelse($brands as $brand)
            <tr>
                @auth
                    @if(in_array($brand['id'], array_column(Auth::user()->followedBrands()->get()->toArray(), 'id')))
                        <td><a href="{{route('follows.followBrand', ['id'=>$brand['id']])}}" class="btn btn-primary"><i class="fa-solid fa-user-minus" style="color: #ffffff;"></i></a></td>
                    @else
                        <td><a href="{{route('follows.followBrand', ['id'=>$brand['id']])}}" class="btn btn-primary"><i class="fa-solid fa-user-plus" style="color: #ffffff;"></i></a></td>
                    @endif
                @endauth
                @if(str_starts_with($brand['image'],"https"))
                <td><img src="{{$brand['image']}}" alt="{{$brand['name']}} image"></td>
                @else
                <td><img src="{{"storage/".$brand['image']}}" alt="{{$brand['name']}} image" style="height:100px"></td>
                @endif
                <td><a href="{{route('brands.show', ['brand'=>$brand['id']])}}" class="text-decoration-none">{{$brand['name']}}</a></td>
                <td>{{$brand['country']}}</td>
                <td>{{App\Models\Brand::find($brand['id'])->types()->count()}}</td>
            </tr>
        @empty
            <div class="col-12">
                <div class="alert alert-warning" role="alert">
                    No brands found!
                </div>
            </div>
        @endforelse
        </table>
    </div>
</div>
@endsection
