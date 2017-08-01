<?php
namespace Line\Services\Query;

class QueryWhereDto{
	private $whereParams;
	public function __construct(array $where){
		$this->whereParams = $where;
	}

	/**
	 * 构建基础查询（针对Kktline）
	 */
	public function getLineWh(){
		$result = [];
		$keys = ['title', 'day_num'];
		foreach($this->whereParams as $key=>$value){
			if($value && in_array($key, $keys)){
				if($key == 'title'){
					$result[] = [$key, 'like', '%'.$value.'%'];
				}else{
					$result[] = [$key, '=', $value];
				}
			}
		}
		return $result;
	}


	/**
	 * 构建行程查询
	 */
	public function getTourWh(){
		return [];
	}
}