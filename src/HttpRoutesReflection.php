<?php
namespace Line;

class HttpRoutesReflection {
	public function getRoutes(){
		$result = array();
		$httpDir = __DIR__.'/Contact/Http';
		$filenames = scandir($httpDir);
		foreach ($filenames as $dir){
			$dir = $httpDir.'/'.$dir.'/';
			if(is_dir($dir) && file_exists($dir.'routes.php')){
				$var = include $dir.'routes.php';
				if(is_array($var)){
					$result[] =	$var;
				}
			}
		}
		return $result;
	}
}