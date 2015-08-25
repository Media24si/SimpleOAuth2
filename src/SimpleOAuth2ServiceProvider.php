<?php namespace Media24si\SimpleOAuth2;

use Illuminate\Support\ServiceProvider;

class SimpleOAuth2ServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/simpleoauth2.php' => config_path('simpleoauth2.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../database/migrations/' => $this->app->databasePath() . '/migrations'
        ], 'migrations');

        $this->loadViewsFrom(__DIR__.'/Views', 'SimpleOAuth2');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            'Media24si\SimpleOAuth2\Console\CreateClient',
            'Media24si\SimpleOAuth2\Console\ListClients'
        ]);

        $this->app->bind('OAuth2\IOAuth2Storage', 'Media24si\SimpleOAuth2\OAuthStorage');
        $this->app->bind('OAuth2\OAuth2', function ($app) {
            $config = config('simpleoauth2.config') ? config('simpleoauth2.config') : [];
            return new \OAuth2\OAuth2($app->make('OAuth2\IOAuth2Storage'), $config);
        });
    }

}