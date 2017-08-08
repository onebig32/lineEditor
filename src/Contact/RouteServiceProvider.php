<?php
namespace Line\Contact;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider{
	public function boot(){
		parent::boot();
	}
	
	public function map(){
		$this->mapWebRoutes();
	}
	
	protected function mapWebRoutes(){
		Route::middleware('web')
		->namespace('Line\Contact\Http')
		->group(__DIR__.'/Http/routes.php');
	}
	
}