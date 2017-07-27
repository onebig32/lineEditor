<?php
namespace Line/Services/Query;

class QueryWhereDto{
	private $where;
	public function __contruct(array $where){
		$this->where = $where;
	}

	/**
	 * 构建基础查询（针对Kktline）
	 */
	public function getBaseWh(){
		$keys = ['id', 'name'];

		$result[] = [$key, '=', $value];
	}

	/**
	 * 构建行程查询
	 */
	public function getTourWh(){
		return [];
	}
}