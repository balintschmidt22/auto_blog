@extends('layouts.app')
@section('title', 'Brands')

@section('content')
<div class="container">
    <h1>Brands - {{count($brands)}}</h1>
    @auth
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
        @auth
            @if(Auth::user()->isAdmin())
                <a href="{{route('brands.create')}}" class="btn btn-primary mb-2 mt-1">Add brand</a>
                <a href="{{route('types.create')}}" class="btn btn-primary mb-2 mt-1 m-1">Add type</a>
            @endif
        @endauth
        <input type="text" size="25" id="searchInput" placeholder="Search brand..." class="float-left m-2">
    </div>
    <div class="row">
        <div class="col">
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
        <div class="col-lg-4">
            <div>
                <table class="table table-bordered float-left" id="foundBrands">
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('input', function () {
            const query = searchInput.value;

            const tab = document.getElementById("foundBrands")
            tab.innerHTML = ""

            axios.post('/brands/search', {
                params: {
                    search: query,
                },
            })
            .then((response) => {
                // Update the data list with the filtered data
                var brands = Object.values(response.data);

                var brandNames = {}
                brands.forEach(b => {
                    brandNames[b['id']] = b['name']
                })

                tab.innerHTML = ""

                for (var id in brandNames){
                    var row = document.createElement("tr")
                    var col = document.createElement("td")
                    var a = document.createElement("a")

                    col.innerHTML = "<a href='brands/" + id + "' class='text-decoration-none'>" + brandNames[id] + "</a>"
                    row.appendChild(col)
                    tab.appendChild(row)
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
        });
    });
</script>
@endsection
