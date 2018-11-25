<?php namespace Hamedmehryar\SessionTracker;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class SessionTrackerServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/config/config.php' => config_path('sessionTracker.php')],'config');
		
		$this->publishes([
			__DIR__.'/migrations' => base_path('database/migrations')
		],'migrations');

		$router = $this->app['router'];
		$router->middleware('session', 'Hamedmehryar\SessionTracker\Middleware\SessionTracker');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$config = __DIR__ . '/config/config.php';
		$this->mergeConfigFrom(
			$config, 'sessionTracker'
		);
		$this->registerSessionTracker();
		$this->registerAuthenticationEventHandler();
	}

	/**
	 * Register the the sessionTracker facade.
	 *
	 * @return void
	 */
	private function registerSessionTracker()
	{
		$this->app->bind('sessionTracker', function ($app) {
			return new SessionTracker($app);
		});
	}

	private function registerAuthenticationEventHandler(){

		Event::subscribe('Hamedmehryar\SessionTracker\AuthenticationHandler');
	}

}
