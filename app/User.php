<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFamiliesAttribute()
    {
        return DB::table('users')
            ->join('family_members', 'users.id', '=', 'family_members.member_id')
            ->where('family_members.owner_id', $this->id)
            ->where('family_members.status', 1)
            ->select('users.id')
            ->inRandomOrder()
            ->get();
        // dd($familyMembers);
    }

    public function getFriendsAttribute()
    {
        return DB::table('users')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('from_id'))
                    ->from('friends')
                    ->where('to_id', $this->id)
                    ->where('status', 1);
            })
            ->orWhereIn('id', function ($query) {
                $query->select(DB::raw('to_id'))
                    ->from('friends')
                    ->where('from_id', $this->id)
                    ->where('status', 1);
            })
            ->select(DB::raw('id'))
            ->inRandomOrder()
            ->get();
        // dd($friends);
    }

    public function family()
    {
        return $this->hasOne('App\Family', 'owner_id');
    }
}
