@extends('layouts.app')
@section('title', 'Brands')

@section('content')
<div class="container">
    <h1>Brands - {{count($brands)}}
        @auth
            @if(Auth::user()->isModerator())
                <a href="{{route('brands.create')}}" class="btn btn-primary mb-2 mt-1">Add brand</a>
                <a href="{{route('types.create')}}" class="btn btn-primary mb-2 mt-1 m-1">Add type</a>
            @endif
        @endauth
    </h1>

    @if(Session::has('brand_deleted'))
        <div class="alert alert-warning" role="alert">
            Brand deleted: {{Session::get('brand_deleted')->name}}
        </div>
    @endif

    <div>
        <a class="btn btn-primary mt-1 mb-2" href="{{ route('brands.pdf.download') }}">Export to PDF</a>
        <a class="btn btn-primary mt-1 mb-2 m-1" href="{{ route('brands.csv.download') }}">Export to CSV</a>
        <input type="text" size="25" id="searchInput" placeholder="Search brand..." class="float-left mb-2 mt-1">
        <input type="text" size="25" id="typeInput" placeholder="Search type..." class="float-left mb-2 mt-1 ml-2">
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-striped">
                <tr class="table-primary">
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Types</th>
                    <th>Follow</th>
                </tr>
            @forelse($brands as $brand)
                <tr>
                    @if(str_starts_with($brand['image'],"placeholder"))
                        <td><img src="{{$brand['image']}}" alt="{{$brand['name']}} image" style="height:100px"></td>
                    @else
                        <td><img src="{{"storage/".$brand['image']}}" alt="{{$brand['name']}} image" style="height:100px; max-width: 160px;"></td>
                    @endif
                    <td><a href="{{route('brands.show', ['brand'=>$brand['id']])}}" class="text-decoration-none">{{$brand['name']}}</a></td>
                    <td>{{$brand['country']}}</td>
                    <td>{{App\Models\Brand::find($brand['id'])->types()->count()}}</td>
                    @guest
                        <td><a href="{{route('login')}}" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus" style="color: #ffffff;"></i>
                        </a></td>
                    @endguest
                    @auth
                        @if (Auth::user()->email_verified_at === null)
                            <td><a href="{{route('verification.notice')}}" class="btn btn-primary">
                                    <i class="fa-solid fa-user-plus" style="color: #ffffff;"></i>
                            </a></td>
                        @else
                            <td><a class="btn btn-primary followButton" id="{{$brand['id']}}">
                                @if(in_array($brand['id'], array_column(Auth::user()->followedBrands()->get()->toArray(), 'id')))
                                    <i class="fa-solid fa-user-minus" style="color: #ffffff;" id="unfollowed"></i>
                                @else
                                    <i class="fa-solid fa-user-plus" style="color: #ffffff;" id="followed"></i>
                                @endif
                            </a></td>
                        @endif
                    @endauth
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
        <div class="col-lg-4">
            <div>
                <table class="table table-bordered float-left" id="foundBrands">
                </table>
                <table class="table table-bordered float-left" id="foundTypes">
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{asset('js/brandAndTypeSearch.js')}}"></script>
    <script src="{{asset('js/followBrand.js')}}"></script>
@endsection
