<?php
namespace Line\Services\Query\Line;

class QueryWhereDto{
	private $whereParams;
	public function __construct(array $where){
		$this->whereParams = $where;
	}

	/**
	 * 构建基础查询（针对Kktline）
	 */
	public function wh(){
		$result = [];
		$eqKeys = ['dest_city_pid','is_draft','day_num'];
		$likeKeys = ['title'];
		$inKeys = ['user_ids'=>'created_user_id'];
		foreach($this->whereParams as $key=>$value){
			if(isset($value) && in_array($key, $eqKeys)){
				$result[] = [$key, '=', $value];
			}
			if(isset($value) && $value && in_array($key, $likeKeys)){
				$result[] = [$key, 'like', '%'.$value.'%'];
			}
			if(isset($value) && $value && in_array($key, $inKeys)){
				$result[] = [$inKeys[$key], 'in', $value];
			}
		}
		return $result;
	}

	/**
	 * 构建关系查询（针对Kktline）
	 */
	public function relation(){
		return ['days'];
	}

	public function orderBy(){
		return ['id', 'desc'];
	}

}