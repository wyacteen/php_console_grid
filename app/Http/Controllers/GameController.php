<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Game;
use App\Picture;
use App\User;


class GameController extends Controller
{

    public function search(Request $request) {
        $query = $request->Input('query');
        $results = Game::search($query)->paginate(30);

        return view('game_search', [
            'games' => $results,
        ]);
        
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
        
        $hasPictures = !empty($pictureData);
        $topRated = array_shift($pictureData);

        return view('game', [
            'hasPictures' => $hasPictures,
            'game' => $game,
            'console' => $console,
            'topRated' => $topRated,
            'pictures' => $pictureData,
            'imageRoot' => '/images/game_images',
        ]);
    }

    /**
     * Uploads a game image and  and puts it into the public
     * game images folder. Also saves a Picture record in the
     * database.
     *
     * @param Request $request
     * @param int $gameid -- ID of the game to add a picture for
     *
     */
    public function uploadImage(Request $request, $gameid) {
        $game = Game::find($gameid);
        
        $image = $request->file('fileToUpload');
        $valid = $image->isValid();

        if ($valid && $game) {
            $user = Auth::user();
            $name = GameController::getNextImageName($game);
            $name = $name . '.' . $image->extension();
            Storage::disk('image_upload')->put($name, file_get_contents($image->getRealPath()), 'public');
            $picture = new Picture;
            $picture->image_name = $name;
            $picture->game()->associate($game);
            $picture->user()->associate($user);
            $picture->save();
        }
        return redirect("/games/$gameid");
    }

    /**
    *   Gets the next game image name. Given a game, looks
    *   through all of the existing game pictures and determines
    *   a unique name for the next image.  Assumes that games
    *   are named with the form 'console'_'game'_'index'.ext, ex.
    *   Wii_Yoga_3.png.
    *
    *   @param Game $game
    *
    *   @return string -- the next game image name.
    */
    public static function getNextImageName(Game $game) {
        $nextImageName;
        $consoleName = $game->console->shortname;
        $pictures = $game->pictures;

        if ($pictures) {
            $existingFileNumbers = [];
            $pattern = "/.*_(\d+)\.\w{3}$/";
            foreach ($pictures as $picture) {
                if (preg_match_all($pattern, $picture->image_name, $matches_out)) {
                    array_push($existingFileNumbers, $matches_out[1][0]);
                }

            }
        }
        $largestFileNumber = count($existingFileNumbers) > 0 ? max($existingFileNumbers) + 1 : 0;
        $nextImageName = sprintf("%s_%s_%d", $consoleName, $game->name, $largestFileNumber);
        return $nextImageName;
    }

}
