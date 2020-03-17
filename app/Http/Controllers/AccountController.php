<?php

namespace App\Http\Controllers;

use App\Post;
use App\Relationship;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
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
     * Show the family dashboard.
     */
    public function familyIndex()
    {
        // Get Users in random order excempting this user
        // SELECT u.name, r.* FROM users u left join relationships r on u.id = r.friend_id where r.is_family_member IS NULL
        $usersWithNoRelationship = DB::table('users')
            ->leftJoin('relationships', 'users.id', '=', 'relationships.friend_id')
            ->where('users.id', '<>', Auth::id())
            ->where('is_family_member', null)
            ->select('users.*')
            ->inRandomOrder()
            ->get();

        // Get Family Member in random order
        //   SELECT u.name, r.* FROM users u join relationships r on u.id = r.friend_id where r.user_id = 3
        $familyMembers = DB::table('users')
            ->join('relationships', 'users.id', '=', 'relationships.friend_id')
            ->where('relationships.user_id', Auth::id())
            ->where('is_family_member', 1)
            ->select('users.*')
            ->inRandomOrder()
            ->get();

        return view('family', ['users' => $usersWithNoRelationship, 'familyMembers' => $familyMembers]);
    }

    /**
     * Show the friends dashboard.
     */
    public function friendIndex()
    {
        // Get Users in random order excempting this user
        // SELECT u.name, r.* FROM users u left join relationships r on u.id = r.friend_id where r.is_family_member IS NULL
        $usersWithNoRelationship = DB::table('users')
            ->leftJoin('relationships', 'users.id', '=', 'relationships.friend_id')
            ->where('users.id', '<>', Auth::id())
            ->where('is_friend', null)
            ->select('users.*')
            ->inRandomOrder()
            ->get();


        // Get Friend in random order
        //   SELECT u.name, r.* FROM users u join relationships r on u.id = r.friend_id where r.user_id = 3
        $friends = DB::table('users')
            ->join('relationships', 'users.id', '=', 'relationships.friend_id')
            ->where('relationships.user_id', Auth::id())
            ->where('is_friend', 1)
            ->select('users.*')
            ->inRandomOrder()
            ->get();

        return view('friends', ['users' => $usersWithNoRelationship, 'friends' => $friends]);
    }

    /**
     * Create Family Account
     */
    public function createFamilyAccount()
    {
        // Create Family Account
        User::where('id', Auth::id())
            ->update(['has_family_account' => 1]);

        // Return back to Family Account
        return back();
    }

    public function addToFamily(Request $request)
    {
        Relationship::create(['user_id' => Auth::id(), 'friend_id' => $request->id, 'is_family_member' => 1]);
        return back();
    }

    public function removeFromFamily(Request $request)
    {
        Relationship::where(['user_id' => Auth::id(), 'friend_id' => $request->id])->forceDelete();
        return back();
    }

    public function addToFriends(Request $request)
    {
        Relationship::create(['user_id' => Auth::id(), 'friend_id' => $request->id, 'is_friend' => 1]);
        return back();
    }

    public function removeFromFriends(Request $request)
    {
        Relationship::where(['user_id' => Auth::id(), 'friend_id' => $request->id])->forceDelete();
        return back();
    }
}
