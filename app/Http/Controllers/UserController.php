<?php

namespace App\Http\Controllers;

use App\Models\User;
//use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PDF;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all()->sortBy('username')->toArray();
        return view('users.search', compact('users'));
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
        // $idExists = User::where('id', $id)->exists();

        // abort_unless($idExists, 404, 'ID not found!');

        return view('users.show', [
            $user = User::findOrFail($id),
            'user' => $user,
            $imgs = $user->ownImages(),
            'image_count' => count($imgs->get()->toArray()),
            'images' => $imgs->with(['type', 'user'])->orderBy('created_at', 'DESC')->paginate(12),
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

    public function search(Request $request)
    {
        $q = $request->all();
        $query = $q['params']['search'];

        $users = collect(User::all());
        $filteredUsers = $users->filter(function ($item) use ($query) {
            return str_contains($item['username'], $query) !== false;
        });

        return $filteredUsers;
    }

    public function createPDF()
    {
        $users = User::get()->sortBy('username');
        $pdf = PDF::loadView('users.pdf', compact('users'));
        return $pdf->download('autoblog_users.pdf');
    }

    public function exportCSV(Request $request)
    {
        $fileName = 'users.csv';
        $users = User::all()->sortBy('username');

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Username', 'Email', 'Country', 'Images', 'Registered');

        $callback = function () use ($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                $row['Username'] = $user->username;
                $row['Email'] = $user->email;
                $row['Country'] = $user->country;
                $row['Images'] = $user->ownImages()->count();
                $row['Registered'] = $user->created_at;

                fputcsv($file, array($row['Username'], $row['Email'], $row['Country'], $row['Images'], $row['Registered']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
