<?php

namespace App\Http\Controllers;

use App\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
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
     * Show the friends dashboard.
     */
    public function index()
    {
        // Get Users in random order excempting this user
        // select * from users where id not in (select from_id from friends where from_id = 7 or to_id = 7) and id not in (select to_id from friends where from_id = 7 or to_id = 7)
        $users = DB::table('users')
            ->whereNotIn('id', function ($query) {
                $query->select(DB::raw('from_id'))
                    ->from('friends')
                    ->where('from_id', Auth::id())
                    ->orWhere('to_id', Auth::id());
            })
            ->whereNotIn('id', function ($query) {
                $query->select(DB::raw('to_id'))
                    ->from('friends')
                    ->where('from_id', Auth::id())
                    ->orWhere('to_id', Auth::id());
            })
            ->where('id', '<>', Auth::id())
            ->get();

        // Get Friends in random order
        // select * from users u where id in (select from_id from friends where to_id = 7 and status = 1) or id in (select to_id from friends where from_id = 7 and status = 1)
        $friends = DB::table('users')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('from_id'))
                    ->from('friends')
                    ->where('to_id', Auth::id())
                    ->where('status', 1);
            })
            ->orWhereIn('id', function ($query) {
                $query->select(DB::raw('to_id'))
                    ->from('friends')
                    ->where('from_id', Auth::id())
                    ->where('status', 1);
            })
            ->inRandomOrder()
            ->get();

        // Get pending friend requests for this user in random order
        $outgoingRequests = DB::table('users')
            ->join('friends', 'users.id', '=', 'friends.to_id')
            ->where('friends.from_id', Auth::id())
            ->select('users.*')
            ->where('friends.status', 0)
            ->inRandomOrder()
            ->get();

        // Incoming Friend Request to User
        $incomingRequests = DB::table('users')
            ->join('friends', 'users.id', '=', 'friends.from_id')
            ->where('friends.to_id', Auth::id())
            ->where('friends.status', 0)
            ->select('users.*')
            ->inRandomOrder()
            ->get();

        return view('friends', ['users' => $users, 'friends' => $friends, 'outgoing' => $outgoingRequests, 'incoming' => $incomingRequests]);
    }

    public function sendFriendRequest(Request $request)
    {
        Friend::create(['from_id' => Auth::id(), 'to_id' => $request->id, 'status' => 0]);
        return back();
    }

    public function acceptFriendRequest(Request $request)
    {
        Friend::where(['from_id' => $request->id, 'to_id' => Auth::id()])->update(['status' => 1]);
        return back();
    }

    public function cancelFriendRequest(Request $request)
    {
        Friend::where(['to_id' => $request->id, 'from_id' => Auth::id()])->delete();
        return back();
    }

    public function rejectFriendRequest(Request $request)
    {
        Friend::where(['from_id' => $request->id, 'to_id' => Auth::id()])->orWhere(['from_id' => Auth::id(), 'to_id' => $request->id])->delete();
        return back();
    }

    public function unfriend(Request $request)
    {
        Friend::where(['to_id' => $request->id, 'from_id' => Auth::id()])->delete();
        return back();
    }
}
