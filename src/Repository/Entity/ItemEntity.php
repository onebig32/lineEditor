<?php
namespace Line\Repository\Entity;

use Line\Repository\Orm\KktLineItem;
use Line\Repository\AbstractEntity;

class ItemEntity extends AbstractEntity{
	private $orm;
	public function __construct(KktLineItem $orm=null){
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
