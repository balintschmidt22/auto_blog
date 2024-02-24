<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only('addComment');
        $this->middleware(["can:moderator"])->only('destroy');
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
     * Save the new comment.
     */
    public function addComment(Request $request, string $id)
    {
        $data = $request->validate(
            [
                'comment' => ['required', 'string', 'min:1', 'max: 2000'],
            ]
        );

        $user = Auth::user()->id;

        $comment = new Comment;
        $comment->comment = $data['comment'];

        $comment->user()->associate(
            $user
        );

        $comment->image()->associate(
            $id
        );

        $comment->save();

        Session::flash('comment_added', $comment);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function delete(string $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        Session::flash('comment_deleted', $comment);

        return redirect()->back();
    }
}
