<?php
namespace Line\Services\Query\Item;

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

	/**
	 *  获取标题
	 */
	public function getTitle(){
		$result = null;
		if(!$this->orm){
			$result = null;
		}else if(isset($this->orm->title)){
			$result = $this->orm->title;
		}
		return $result;
	}

	/**
	 *  获取id
	 */
	public function getId(){
		$result = null;
		if(!$this->orm){
			$result = null;
		}else if(isset($this->orm->id)){
			$result = $this->orm->id;
		}
		return $result;
	}

	/**
	 *  获取id
	 */
	public function getImgs(){
		$result = null;
		if(!$this->orm){
			$result = null;
		}else if(isset($this->orm->imgs)){
			$result = $this->orm->imgs;
		}
		return $result;
	}
}
