<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Vote;

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

    public function votes() {
        return $this->hasMany('App\Vote');
    }

    public function votedForPicture($picture) {
        return $this->votes()
            ->where('votes.picture_id', '=', $picture->id)
            ->get()
            ->isNotEmpty();
    }

    public function getUserVoteForPicture($picture) {
        return $this->votes()
            ->where('votes.picture_id', '=', $picture->id)
            ->get()
            ->first();
    }
}
