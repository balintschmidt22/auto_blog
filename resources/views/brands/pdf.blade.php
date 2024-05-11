<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Brands PDF</title>
    <style>
        th {
            text-align: center;
            vertical-align: middle;
            background: lightgray
        }
        td {
            text-align: center;
            vertical-align: middle;
        }
        table{
            border: 1px solid black;
        }
        h1{
            margin-bottom: 1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Brands - {{count($brands)}}</h1>
        <div class="row">
            <div class="col">
                <div>
                    <table>
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Country</th>
                            <th>Last Modified</th>
                        </tr>
                    @forelse($brands as $brand)
                        <tr>
                            @if(str_starts_with($brand['image'],"placeholder"))
                                <td><img src="{{$brand['image']}}" alt="{{$brand['name']}} image" style="height:60px"></td>
                            @else
                                <td><img src="{{"storage/".$brand['image']}}" alt="{{$brand['name']}} image" style="height:60px; max-width: 100px;"></td>
                            @endif
                            <td><a href='{{route('brands.show', ['brand'=>$brand['id']])}}'>{{$brand['name']}}</a></td>
                            <td>{{$brand['country']}}</td>
                            <td>{{$brand['updated_at']}}</td>
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
        </div>
    </div>
</body>
</html>


