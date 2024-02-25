<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function followUser(string $id)
    {
        $user = Auth::user();

        $follow = User::findOrFail($id);

        $follows = $user->follows()->get()->toArray();
        $f_ids = array_column($follows, 'id');

        if (!in_array($follow['id'], $f_ids)) {
            $user->follows()->sync($follow, false);

            Session::flash('followed', $follow);
        } else {
            $user->follows()->detach($follow);

            Session::flash('unfollowed', $follow);
        }

        return redirect()->back();
    }
}
