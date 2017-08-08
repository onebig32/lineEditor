<?php
namespace Line\Services\Query\Img;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * 查询结果处理 DTO
 * @author g
 *
 */
class QueryResultDto{
	private $orm;
	public function __construct($orm){
		$this->orm = $orm;
	}
		
	/**
	 * 获取基础数据
	 * @param array $column 限定字段 为空不限定字段
	 */
	public function baseData(array $column=[]){
		$result = null;
		if(!$this->orm){
			$result = null;
		}else if($this->orm instanceof Model){
			if(!empty($column)){
				$result = array_only($this->orm->toArray(), $column);
			}else{
				$result = $this->orm->toArray();				
			}
		}else if($this->orm instanceof Collection){
			foreach($this->orm as $obj){
				if(!empty($column)){
					array_push($result, array_only($obj->toArray()));					
				}else{
					array_push($result, $obj->toArray());
				}
			}
		}
		return $result;
	}

}
