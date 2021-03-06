<?php
namespace Line;

use Illuminate\Support\ServiceProvider;

class LineServiceProvider extends ServiceProvider{
	public function boot(){
		$this->loadMigrationsFrom(__DIR__.'/database/migrations');
	}
	
	public function register(){
		$provides =  [
			'Line\Contact\RouteServiceProvider',
		];
		foreach ($provides as $provider) {
			$this->app->register($provider);
		}


	}
}
