<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
//use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "verified"])->only(['message', 'addMessage', 'useredit', 'userUpdate', 'changePassword', 'updatePassword']);
        $this->middleware(["can:admin"])->only(['delete', 'addModerator', 'removeModerator', 'edit', 'update']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all()->sortBy('username', SORT_NATURAL | SORT_FLAG_CASE)->toArray();
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

        $user = User::findOrFail($id);
        $likesGiven = count($user->likedImages()->get()->toArray());
        $imgs = $user->ownImages();
        $likedBy = 0;
        $commentsGot = 0;
        foreach ($imgs->get() as $i) {
            $likedBy += count($i->likedBy()->get()->toArray());
            $commentsGot += count($i->comments()->get()->toArray());
        }
        $commentedOn = count($user->commentedOn()->get()->toArray());

        return view('users.show', [
            'user' => $user,
            'image_count' => count($imgs->get()->toArray()),
            'images' => $imgs->with(['type', 'user'])->orderBy('created_at', 'DESC')->paginate(12),
            'likesGiven' => $likesGiven,
            'likedBy' => $likedBy,
            'followedBy' => count($user->followedBy()->get()->toArray()),
            'follows' => count($user->follows()->get()->toArray()),
            'followedBrands' => count($user->followedBrands()->get()->toArray()),
            'commentedOn' => $commentedOn,
            'commentsGot' => $commentsGot,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('users.edit', [
            'user' => User::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'unique:users,username,' . $id],
                'country' => ['required', 'string'],
                'email' => ['required', 'email:rfc,dns', 'unique:users,email,' . $id],
                'image' => ['file', 'image', 'max: 4096'],
            ]
        );
        $user = User::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($user['profile_picture'] !== null) {
                if (str_starts_with($user['profile_picture'], "https"))
                    $user['profile_picture'] = "";
                else {
                    unlink(public_path() . "/storage/" . $user['profile_picture']);
                }
            }

            $file = $data['image'];

            $image = $file->store('images', ['disk' => 'public']);

            $user->profile_picture = $image;
        }

        $user->update(['username' => $data['name'], 'country' => $data['country'], 'email' => $data['email']]);

        if ($user->wasChanged()) {
            Session::flash('user_edited', $user);
        }


        return redirect('users/' . $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $user = User::findOrFail($id);
        if ($id == 1) {
            abort(404);
        }
        $user->delete();

        Session::flash('user_deleted', $user);

        return redirect('/users');
    }

    public function search(Request $request)
    {
        $q = $request->all();
        $query = $q['params']['search'];

        $users = User::all();
        if (trim($query) !== "") {
            $filteredUsers = $users->filter(function ($item) use ($query) {
                return str_contains(strtolower($item['username']), strtolower($query)) !== false;
            })->sortBy('username', SORT_NATURAL | SORT_FLAG_CASE)->values();
        } else {
            $filteredUsers = [];
        }

        return $filteredUsers;
    }

    public function createPDF()
    {
        $users = User::get()->sortBy('username', SORT_NATURAL | SORT_FLAG_CASE);
        $pdf = \Barryvdh\DomPDF\Facade\PDF::loadView('users.pdf', compact('users'));
        return $pdf->download('autoblog_users.pdf');
    }

    public function exportCSV(Request $request)
    {
        $fileName = 'users.csv';
        $users = User::all()->sortBy('username', SORT_NATURAL | SORT_FLAG_CASE);

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

                fputcsv($file, array ($row['Username'], $row['Email'], $row['Country'], $row['Images'], $row['Registered']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function message(string $id)
    {
        $user = Auth::user();
        $otherUser = User::findOrFail($id);

        if ($user['id'] == $id) {
            abort(404);
        }

        $messagesSent = $user->messagesSent()->get()->where('to_id', '=', $id);
        $messagesReceived = $user->messagesReceived()->get()->where('from_id', '=', $id);

        $messages = $messagesSent->concat($messagesReceived)->values();

        return view('messages.index', [
            'messages' => $messages->sortByDesc('created_at'),
            'otherUser' => $otherUser,
        ]);
    }

    public function addMessage(Request $request, string $id)
    {
        $data = $request->validate(
            [
                'message' => ['required', 'string', 'min:1', 'max: 2000'],
            ]
        );

        $user = Auth::id();

        if ($user == $id) {
            abort(404);
        }

        $message = new Message;
        $message->message = $data['message'];

        $message->from()->associate(
            $user
        );

        $message->to()->associate(
            $id
        );

        $message->save();

        Session::flash('message_sent', $message);

        return redirect()->back();
    }

    public function addModerator(string $id)
    {
        $user = User::findOrFail($id);

        if (!$user->isModerator() && !$user->isAdmin()) {
            $user['role'] = "mod";
            $user->save();

            Session::flash('moderator_added', $user);
        }

        return redirect('users/' . $user['id']);
    }

    public function removeModerator(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->isModerator() && !$user->isAdmin()) {
            $user['role'] = "usr";
            $user->save();

            Session::flash('moderator_removed', $user);
        }

        return redirect('users/' . $user['id']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function useredit(string $id)
    {
        if (Auth::id() != $id) {
            abort(403);
        }
        return view('users.useredit', [
            'user' => User::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function userUpdate(Request $request, string $id)
    {
        if (Auth::id() != $id) {
            abort(403);
        }
        $data = $request->validate(
            [
                'country' => ['required', 'string'],
                'image' => ['file', 'image', 'max: 4096'],
            ]
        );
        $user = User::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($user['profile_picture'] !== null) {
                if (str_starts_with($user['profile_picture'], "https"))
                    $user['profile_picture'] = "";
                else {
                    unlink(public_path() . "/storage/" . $user['profile_picture']);
                }
            }

            $file = $data['image'];

            $image = $file->store('images', ['disk' => 'public']);

            $user->profile_picture = $image;
        }

        $user->update(['country' => $data['country']]);

        if ($user->wasChanged()) {
            Session::flash('user_edited_by_themself', $user);
        }


        return redirect('users/' . $id);
    }

    public function changePassword(string $id)
    {
        if (Auth::id() != $id) {
            abort(403);
        }
        return view('users.password');
    }

    public function updatePassword(Request $request, string $id)
    {
        if (Auth::id() != $id) {
            abort(403);
        }

        $data = $request->validate(
            [
                'old_password' => ['required'],
                'new_password' => [
                    'required',
                    'between:8,255',
                    'confirmed'
                ]
            ]
        );

        $user = User::findOrFail($id);

        if (!Hash::check($data['old_password'], Auth::user()->password)) {
            Session::flash('password_error');

            return redirect()->back();
        }

        $user->update(['password' => Hash::make($data['new_password'])]);

        Session::flash('password_changed');

        return redirect()->back();
    }
}
