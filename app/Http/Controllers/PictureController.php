<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Game;
use App\Picture;
use App\Vote;

class PictureController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function vote($pictureID, $method) {
        $picture = Picture::find($pictureID);
        $game = $picture->game;

        if($this->isValidMethod($method)) {

            $user = Auth::user();
            $vote = $user->getUserVoteForPicture($picture);

            if (!isset($vote)) {
                $vote = new Vote;
                $vote->user()->associate($user);
                $vote->game()->associate($game);
                $vote->picture()->associate($picture);
            }
            
            if ($method == 'up') {
                $vote->value = 1;
            }
            else if ($method == 'down') {
                $vote->value = -1;
            }
            
            $vote->save();

        }
        else {
            return redirect("/");
        }
        // Redirect back to the game view
        $gameID = $game->id;
        return redirect("/games/$gameID");
    }

    // this should be a static method or a utility
    protected function isValidMethod($method) {
        return $method == 'up' || $method == 'down';
    }
}
