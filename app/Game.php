<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{   
    use Searchable;

    public function console() {
        return $this->belongsTo('App\Console');
    }

    public function pictures() {
        return $this->hasMany('App\Picture');
    }

    public function topRatedPicture() {
        $topRatedPicture = NULL;
        if ($this->pictures->isNotEmpty()) {
            $topRatedPicture = $this->pictures->sortByDesc('netVotes')->first();
        }
        return $topRatedPicture;
    }

    public function toSearchableArray() {
        $game_data = $this->toArray();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'console_name' => $this->console->name,
        ];
    }

    /**
     * Get the index name for the Game model.
     *
     * @return string
     */
    public function searchableAs() {
        return 'games_index';
    }
}
