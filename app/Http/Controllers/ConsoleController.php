<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Console;
use App\Game;

class ConsoleController extends Controller
{
    /**
     * Given a console id list all of the games
     */
    public function listGames($id) {
        $console = Console::find($id);
        $games = $console->games()->orderBy('name')->paginate(30);

        return view('games', [
            'console' => $console,
            'games' => $games,
        ]); 
    }


}
