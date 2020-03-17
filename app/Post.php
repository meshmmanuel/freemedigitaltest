<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content', 'user_id', 'visible_by'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getIsVisibleAttribute()
    {
        dump($this->user);
    }
}
