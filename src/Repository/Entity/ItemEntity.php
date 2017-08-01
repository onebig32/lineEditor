<?php
namespace Line\Repository\Entity;

use Line\Repository\Orm\KktLineTourItem;
use Line\Repository\AbstractEntity;

class ItemEntity extends AbstractEntity{
	private $orm;
	public function __construct(KktLineTourItem $orm=null){
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
