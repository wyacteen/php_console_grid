<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Vote;

class Picture extends Model
{
    

    /**
     * Gets the vote data for this console
     */
    public function votes() {
        return $this->hasMany('App\Vote');
    }

    // stole this votecount stuff from 
    // https://softonsofa.com/tweaking-eloquent-relations-how-to-get-hasmany-relation-count-efficiently/
    public function netVoteCountRelation() {
        return $this->hasOne('App\Vote')
            ->selectRaw('picture_id, sum(votes.value) as sum')
            ->groupBy('picture_id');
    }

    public function getNetVotesAttribute() {
        return $this->netVoteCountRelation
            ? $this->netVoteCountRelation->sum
            : 0
        ;
    }

    /**
     * Determines if a user voted for this picture
     */
    public function didUserVoteFor($user) {
        $userVotes = $this->votes()
            ->where('user_id', '=', $user->id)
            ->get();

        return $userVotes->count() > 0;
    }
 
    public function game() {
        return $this->belongsTo('App\Game');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    // overriden to delete associated file
    public function delete() {
        
        if ($this->image_name) {
            Storage::disk('image_upload')->delete($this->image_name);
        }

        return parent::delete();
    }

}
