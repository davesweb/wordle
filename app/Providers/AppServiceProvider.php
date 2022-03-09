<?php

namespace App\Providers;

use App\Game\Words\TextWordProvider;
use App\Game\Words\WordProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WordProvider::class, fn() => new TextWordProvider(resource_path('game/words.txt')));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
