<?php

namespace Hinaloe\Laravel\Channels\Facebook;

use Hinaloe\Laravel\Channels\Facebook\Channel\FacebookChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;

class FacebookServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(ChannelManager::class)->extend('facebook', function ($app) {
            return $app->make(FacebookChannel::class);
        });
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
