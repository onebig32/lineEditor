<?php
/*
 |--------------------------------------------------------------------------
 | Web Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register web routes for your application. These
 | routes are loaded by the RouteServiceProvider within a group which
 | contains the "web" middleware group. Now create something great!
 |
 */

$routes = array();
$filenames = scandir(__DIR__);
foreach ($filenames as $dir){
	if($dir == '.' || $dir == '..'){
		continue;
	}
	$dir = __DIR__.'/'.$dir.'/';
	if(is_dir($dir) && file_exists($dir.'routes.php')){
		$var = include_once $dir.'routes.php';
		if(is_array($var)){
			$routes[] =	$var;
		}
	}
}
Route::group(['middleware' => ['web']], function () use($routes){
	foreach($routes as $row){
		foreach($row as $v){
			$restFullType = null;
			if($pos = strstr($v, ':')!==false){
				$pos = strpos($v, ':');
				$end = false;
				if(($end = strpos($v, ':', $pos+1)) !== false){
					$v = substr($v, 0, $end);
				}
				$restFullType = strtolower(substr($v, $pos+1));
				$v = substr($v, 0, $pos);
			}
			$array = explode('.', $v);
			$url = '/'.strtolower($array[0]).'/'.strtolower($array[1]).'/'.strtolower($array[2]);
			$method = $array[0].'\\'.$array['1'].'@'.$array[2];
			if(!$restFullType || !in_array($restFullType, ['get','post','delete','put','patch'])){
				if(strtolower(substr(strrev($array[2]),0,4)) == 'xaja'){
					$restFullType = 'post';
				}else{
					$restFullType = 'get';
				}
			}
			Route::$restFullType($url, ['uses'=>$method,'middleware' => 'self.auth']);
		}
	}
});