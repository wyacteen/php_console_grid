<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Picture extends Model
{
    

    /**
     * Gets the vote data for this console
     */
    public function votes() {
        return $this->hasMany(Vote::class);
    }

    public function game() {
        return $this->belongsTo('App\Game');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
