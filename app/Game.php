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

    public function toSearchableArray() {
        $game_data = $this->toArray();
        // customize array
        $game_data['console'] = $this->console;

        return $game_data;
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
