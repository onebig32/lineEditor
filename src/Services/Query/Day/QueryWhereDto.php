<?php
namespace Line\Services\Query\Day;

class QueryWhereDto{
	private $whereParams;
	public function __construct(array $where){
		$this->whereParams = $where;
	}

	/**
	 * 构建基础查询（针对KktlineDay）
	 */
	public function wh(){
		$result = [];
		$eqKeys = ['id'];
		$inKeys = ['ids'=>'id'];

		foreach($this->whereParams as $key=>$value){
			if($value && in_array($key, $eqKeys)){
				$result[] = ['column' =>$key,'operator'=> '=', 'value'=>$value];
			}
			if($value && in_array($key, array_keys($inKeys))){
				$result[] = ['column'=>$inKeys[$key], 'operator'=>'in','value'=> $value];
			}
		}
		return $result;
	}

	/**
	 * 构建关系查询（针对KktlineDay和KktLineDayItems一对多的关系）
	 */
	public function relation(){
		return ['items'];
	}

	/**
	 * 定义排序
	 */
	public function orderBy(){
		return ['id', 'asc'];
	}

}