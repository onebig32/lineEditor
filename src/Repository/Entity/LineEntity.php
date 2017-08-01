<?php
namespace Line\Repository\Entity;

use Line\Repository\Orm\KktLine;
use Line\Repository\AbstractEntity;

class LineEntity extends AbstractEntity{
	private $orm;
	public function __construct(KktLine $orm=null){
		$this->orm = $orm;
		if($orm){
			$this->arrayToProp($orm->toArray());
		}
	}
	
	public function __get($key){
		if(in_array($key, array_keys($this->props))){
			return $this->props[$key];	
		}else if($this->orm){
			return $this->orm->$key;
		}
		return null;
	}
}
