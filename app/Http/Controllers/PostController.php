<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return posts to home
     */
    public function index()
    {
        // Return Post object
        // SELECT * FROM posts p
        // where p.visible_by = 1
        // and (p.user_id in (select from_id from friends f where f.to_id = 3) or p.user_id in (select to_id from friends f where f.from_id = 3))
        // or p.visible_by = 2 and (p.user_id in (select fm.member_id from family_members fm where fm.owner_id = 3))
        // or p.visible_by = 3 and ((p.user_id in (select from_id from friends f where f.to_id = 3) or p.user_id in (select to_id from friends f where f.from_id = 3)) or (p.id in (select fm.member_id from family_members fm where fm.owner_id = 3)))
        //  or user_id = 1

        $posts = DB::table('posts')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('posts.visible_by', 1)
            ->whereIn('posts.user_id', function ($query) {
                $query->select('from_id')
                    ->from('friends')
                    ->where('status', 1)
                    ->where('to_id', Auth::id());
            })
            ->orWhereIn('posts.user_id', function ($query) {
                $query->select('to_id')
                    ->from('friends')
                    ->where('status', 1)
                    ->where('from_id', Auth::id());
            })
            ->orWhere('posts.visible_by', 2)
            ->whereIn('posts.user_id', function ($query) {
                $query->select('member_id')
                    ->from('family_members')
                    ->where('status', 1)
                    ->where('owner_id', Auth::id());
            })
            ->orWhere('posts.visible_by', 3)
            ->whereIn('posts.user_id', function ($query) {
                $query->select('from_id')
                    ->from('friends')
                    ->where('status', 1)
                    ->where('to_id', Auth::id());
            })
            ->orWhereIn('posts.user_id', function ($query) {
                $query->select('to_id')
                    ->from('friends')
                    ->where('status', 1)
                    ->where('from_id', Auth::id());
            })
            ->whereIn('posts.user_id', function ($query) {
                $query->select('member_id')
                    ->from('family_members')
                    ->where('status', 1)
                    ->where('owner_id', Auth::id());
            })
            ->orWhere('posts.user_id', Auth::id())
            ->where('deleted_at', null)
            ->select('users.name', 'posts.*')
            ->get();

        return view('home', ['posts' => $posts]);
    }

    /**
     *
     */
    public function store(Request $request)
    {
        // Validate input
        $this->validate($request, [
            'file' => 'required'
        ]);

        // Set new file name
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
        }

        // Create record and upload file
        $post = Post::create(['content' => $fileName, 'user_id' => Auth::id(), 'visible_by' => $request->visible_by]);
        if ($post) {
            $file->move(public_path('posts'), $fileName);
        }

        // Return Post object
        $post = Post::where('deleted_at', null)
            ->where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        // Return view with data
        return back();
    }

    /**
     * Trash an uploaded file
     */
    public function trash(Request $request)
    {
        Post::where('id', $request->id)->delete();
        return back();
    }
}
