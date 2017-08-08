<?php
namespace Line\Repository;

use Exception;

/**
 * 实体属性类，约定实体属性都为public类型
 */

abstract class AbstractEntity {
	
	protected $props = [];
	
	public function __set($key, $value){
		if(is_object($value)){
			throw new Exception("实体属性只能是字符串或数组");
		}
		$this->props[$key] = $value;
	}

	/**
	 * 实体对象转数组
	 */
	public function toArray(){
		return $this->props;
	}

	/**
	 * 数组值变对象属性
	 */
	public function arrayToProp(array $array){
		foreach($array as $key=>$value){		
			$this->$key = $value;
		}
		return $this;
	}
}