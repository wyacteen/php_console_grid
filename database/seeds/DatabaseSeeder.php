<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Console;
use App\Game;
use App\Picture;
use App\User;
use App\Vote;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        // We need user to associate with pictures and votes.
        $seed_user = new User;
        $seed_user->name = 'seed_user';
        $seed_user->password = Hash::make('password');
        $seed_user->email = 'seed@noemail.com';
        $seed_user->save();
        $console_data_entries = json_decode(file_get_contents('database/seeds/console_data.json'), true);
        
        $console_count = 0;
        foreach ($console_data_entries as $console_entry) {
            $console_count++;
            echo $console_entry['consolename'] . "\n";
            $console = new Console;
            $console->name = $console_entry['consolename'];
            $console->shortname = $console_entry['abbreviation'];
            $console->save();
            printf("Processing console %s -- %u of %u\n", $console->name, $console_count, count($console_data_entries));
     
            foreach ($console_entry['gameData'] as $game_entry) {
                print "found game: " . $game_entry['name'] . "\n";
                $game = new Game;
                $game->name = $game_entry['name'];
                $game->console()->associate($console);
                $game->save();
                $image_count = 0;
                foreach ($game_entry['imageLinks'] as $image_link) {
                    // image name is constructed as console_game_index, ex. wii_mario kart_0_extension
                    // for whatever reason, some entries don't have a source, meaning an image wasn't found for
                    // it. Skip these since they aren't very useful other than to serve as placeholders.
                    if (
                        array_key_exists('source', $image_link)
                        && $image_link['source']
                        && preg_match("/\.(gif|jpg|png)$/", $image_link['source'], $matches)
                    ) {
                        $image_extension = $matches[1];
                        $image_name = sprintf(
                            "%s_%s_%u.%s",
                            $console->shortname,
                            $game->name,
                            $image_count,
                            $image_extension
                        );
                        print "image_name: $image_name\n";
                        $picture = new Picture;
                        $picture->image_name = $image_name;
                        $picture->game()->associate($game);
                        $picture->user()->associate($seed_user);
                        $picture->save();
                        // only add a vote for the first image and only if it's positive. The data file
                        // is already sorted by vote, so this ensures we have the top
                        if (
                            $image_count == 0
                            && array_key_exists('voteCount', $image_link)
                            && $image_link['voteCount'] > 0
                        ) {
                            $vote = new Vote;
                            $vote->picture()->associate($picture);
                            $vote->user()->associate($seed_user);
                            $vote->game()->associate($game);
                            $vote->value = 1;
                            $vote->save();
                        }
                        $image_count++;
                    }
                }
            }
        }
        
        DB::commit();
    }
}
