<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use App\Console;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        // View::composer(
        //     'profile', 'App\Http\ViewComposers\ProfileComposer'
        // );

        // This adds the console data for use in the navbar since every page in the web app
        // has it.
        View::composer('*', function ($view) {
            $consoles = Console::orderby('shortname')->get();
            
            $view->with(compact('consoles'));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}