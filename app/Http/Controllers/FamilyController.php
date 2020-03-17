<?php

namespace App\Http\Controllers;

use App\Family;
use App\FamilyMember;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FamilyController extends Controller
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
     * Show family dashboard.
     */
    public function index()
    {
        // Get Users in random order excempting this user
        // SELECT u.id, u.name FROM users u where u.id not in (select member_id from family_members where owner_id = 4) and id<> 4
        $users = DB::table('users')
            ->whereNotIn('id', function ($query) {
                $query->select(DB::raw('member_id'))
                    ->from('family_members')
                    ->where('owner_id', Auth::id());
            })
            ->where('id', '<>', Auth::id())
            ->get();

        // Pending Family Request
        // Out going famliy invite
        $outgoingRequests = DB::table('users')
            ->join('family_members', 'users.id', '=', 'family_members.member_id')
            ->where('family_members.owner_id', Auth::id())
            ->select('users.*')
            ->where('family_members.status', 0)
            ->inRandomOrder()
            ->get();

        // Incoming Family Request to User
        $incomingRequests = DB::table('users')
            ->join('family_members', 'users.id', '=', 'family_members.owner_id')
            ->where('family_members.member_id', Auth::id())
            ->where('family_members.status', 0)
            ->select('users.*')
            ->inRandomOrder()
            ->get();

        // Get Family Member in random order
        // SELECT * FROM users u inner join family_members f on u.id = f.member_id where f.owner_id= 3
        $familyMembers = DB::table('users')
            ->join('family_members', 'users.id', '=', 'family_members.member_id')
            ->where('family_members.owner_id', Auth::id())
            ->where('family_members.status', 1)
            ->select('users.*')
            ->inRandomOrder()
            ->get();

        // Other Families I belong to
        $otherFamilies = DB::table('users')
            ->join('family_members', 'users.id', '=', 'family_members.owner_id')
            ->where('family_members.member_id', Auth::id())
            ->where('family_members.status', 1)
            ->select('users.*')
            ->inRandomOrder()
            ->get();

        return view('family', ['users' => $users, 'familyMembers' => $familyMembers, 'incoming' => $incomingRequests, 'outgoing' => $outgoingRequests, 'others' => $otherFamilies]);
    }

    /**
     * Create a famliy account
     */
    public function createFamily()
    {
        Family::create(['owner_id' => Auth::id()]);
        return back();
    }

    /**
     * Delete Family
     */
    public function deleteFamily()
    {
        Family::where(['owner_id' => Auth::id()])->delete();
        return back();
    }

    /**
     * Add Member to Family
     */
    public function addToFamily(Request $request)
    {
        FamilyMember::create(['family_id' => Auth::user()->family->id, 'owner_id' => Auth::user()->family->owner_id, 'member_id' => $request->id, 'status' => 0]);
        return back();
    }

    /**
     * Accept incoming family request
     */
    public function acceptFamilyRequest(Request $request)
    {
        FamilyMember::where(['owner_id' => $request->id, 'member_id' => Auth::id()])->update(['status' => 1]);
        return back();
    }

    /**
     * Reject incoming family request
     */
    public function rejectFamilyRequest(Request $request)
    {
        FamilyMember::where(['owner_id' => $request->id, 'member_id' => Auth::id()])->delete();
        return back();
    }

    /**
     * Cancel outgoing family request
     */
    public function cancelFamilyRequest(Request $request)
    {
        FamilyMember::where(['owner_id' => Auth::id(), 'member_id' => $request->id])->delete();
        return back();
    }

    /**
     * Remove existing member from my family
     */
    public function removeFromFamily(Request $request)
    {
        FamilyMember::where(['owner_id' => Auth::id(), 'member_id' => $request->id])->delete();
        return back();
    }

    /**
     * View Member in this family
     */
    public function viewFamilyMembers(Request $request)
    {
        // Other Families I belong to
        $familyMembers = DB::table('users')
            ->join('family_members', 'users.id', '=', 'family_members.member_id')
            ->where('family_members.owner_id', $request->id)
            ->where('family_members.status', 1)
            ->select('users.*')
            ->inRandomOrder()
            ->get();
        $owner = User::find($request->id);

        return view('family-members', ['familyMembers' => $familyMembers, 'owner' => $owner]);
    }
}
