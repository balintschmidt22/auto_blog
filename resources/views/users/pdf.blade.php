<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h1>Users - {{count($users)}}</h1>
        <div class="row">
            <div class="col">
                <div>
                    <table class="bordered" id="usersTable">
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Country</th>
                            <th>Photos</th>
                        </tr>
                    @forelse($users as $user)
                        <tr>
                            <td><a href='{{route('users.show', ['user'=>$user['id']])}}' class="text-decoration-none">{{$user['username']}}</a></td>
                            <td>{{$user['role']}}</td>
                            <td>{{$user['country']}}</td>
                            <td>{{App\Models\User::find($user['id'])->ownImages()->count()}}</td>
                        </tr>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                No users found!
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


