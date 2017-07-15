<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{

    /**
     * Get the user associated with this vote
     */
     public function user() {
         return $this->belongsTo('App\User');
     }

     public function picture() {
         return $this->belongsTo('App\Picture');
     }

     public function game() {
         return $this->belongsTo('App\Game');
     }

    public function isUpVote() {
        return $this->value > 0;
    }

    public function isDownVote() {
        return $this->value < 0;
    }
}
