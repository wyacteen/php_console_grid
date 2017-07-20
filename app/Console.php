<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Game;

class Console extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'shortname'
    ];

    /**
     * Gets the game data for this console
     */
    public function games() {
        return $this->hasMany('App\Game');
    }

}
