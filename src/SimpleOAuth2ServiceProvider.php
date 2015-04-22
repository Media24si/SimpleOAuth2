<?php namespace Media24si\SimpleOAuth2;

use Illuminate\Support\ServiceProvider;

class SimpleOAuth2ServiceProvider extends ServiceProvider  {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__.'/../config/simpleoauth2.php' => config_path('simpleoauth2.php'),
		]);

		$this->publishes([
			__DIR__. '/../database/migrations/' => $this->app->databasePath() . '/migrations'
		], 'migrations');
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
		//$this->registerViews();
	}

	/**
	 * Register package views
	 */
	/*private function registerViews() {
		$this->loadViewsFrom(__DIR__ . '/resources/views/', 'RouteExplorer');
	}*/
}