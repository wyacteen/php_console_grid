<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Game;
use App\Picture;
use App\User;


class GameController extends Controller
{

    public function search(Request $request) {
        $query = $request->Input('q');
        // We don't support search. For now just print the query.
        print $query;
    }

    public function find($id) {

        $game = Game::find($id);
        $console = $game->console;
        $pictures = $game->pictures;
        $pictures = $pictures->sortByDesc('netVotes');

        $user = Auth::user();

        $pictureData = [];
        foreach ($pictures as $picture) {
            $vote = isset($user) ? $user->getUserVoteForPicture($picture) : NULL;
            $pictureInfo = [
                'picture' => $picture,
                'userUpVoted' => $vote && $vote->isUpVote(),
                'userDownVoted' => $vote && $vote->isDownVote(),
                'vote' => $vote,
            ];
            array_push($pictureData, $pictureInfo);
        }

        $topRated = array_shift($pictureData);

        return view('game', [
            'hasPictures' => True,
            'game' => $game,
            'console' => $console,
            'topRated' => $topRated,
            'pictures' => $pictureData,
            'imageRoot' => '/images/game_images',
        ]);
    }

}
