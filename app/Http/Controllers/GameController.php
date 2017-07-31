<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

use App\Game;
use App\Picture;
use App\User;
use App\Console;


class GameController extends Controller
{

    public function search(Request $request) {
        $query = $request->Input('query');
        $results = Game::search($query)->paginate(30);

        return view('game_search', [
            'games' => $results,
        ]);
        
    }

    /**
     * Handles top_picture requests from API requests.
     */
    public function topPictureSearch(Request $request) {
        $gameName = $request->Input('game');
        $consoleShortName = $request->Input('console');
        Log::info('top_picture: game: ' . $gameName . ' console: ' . $consoleShortName);
        return $this->findTopPicture($gameName, $consoleShortName);

    }


    /**
     * Performs a case-insensitive search for the top rated
     * picture URL for the given game on the specificed console.
     * An exact match for the abbreviated console name be found.
     *
     * If a console is found, attempts to find an exact match using
     * the game name. If an exact match isn't found uses TNTSearch
     * to find potential matches and then uses the levenshtein distance
     * to determine the closes match. If one is found that has at least
     * one picture, returns the URL of the top-voted picture.
     *
     * @TODO: This was written this way because the original consolegrid
     * site allowed for non-exact matches. Consider only allowing for
     * exact console and game name matches. Also, this search
     * method is a bit sloppy, there's lots of room for improvement.
     *
     * @param string $gameName          - The name of the game
     * @param string $consoleShortName  - The abbreviated name of the console (ex. NES)
     *
     * @return string, the URL of the top-rated picture. If none found
     * returns an empty string.
     */
    public function findTopPicture($gameName, $consoleShortName) {

        // Find the console.
        $console = Console::where('shortname', '=', $consoleShortName)->first();

        $url = '';
        if ($console) {
            // Look for an exact match on the game name.
            $matchingGame = Game::where('console_id', '=', $console->id)
                ->where('name', '=', $gameName)->get()->first();

            $topRatedPicture = NULL;

            // Perform fuzzy search if no exact matches;
            if (!$matchingGame) {
                // Use TNT search to find all matches on the given console.
                $matchingGames = Game::search($gameName)->where('console_id', $console->id)->get();

                // Use levenshtein distance to determine closest matches. This is inefficient
                // but there shouldn't too many matches.
                $closestMatches = $matchingGames->sortBy(function($game, $key) use($gameName) {
                    return levenshtein(strtolower($gameName), strtolower($game->name));
                });

                $matchingGame = $closestMatches->first();
            }

            if ($matchingGame && $matchingGame->topRatedPicture()) {
                $topRatedPicture = $matchingGame->topRatedPicture();
                $url = sprintf("%s/%s", 'http://192.168.1.9/images/game_images', $topRatedPicture->image_name);                
            }
        }

        return $url;
    }

    /**
     * Find a game by id and route to view that
     * can display its details.
     *
     * @param $id -- the id of the gave to view
     *
     */
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
