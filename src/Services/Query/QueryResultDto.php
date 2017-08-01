<?php
namespace Line\Services\Query;

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
	 * 获取外键id数组
	 */
	public function getForeignIds(){
		if(!$this->orm){
			return null;
		}else if($this->orm instanceof Model){
			return [$this->orm->foreign_id];
		}else if($this->orm instanceof Collection){
			$result = [];
			foreach($this->orm as $obj){
				array_push($result, $obj->foreign_id);
			}
			return $result;
		}
		return null;
	}
	
	/**
	 * 获取用户数量
	 */
	public function getUserNum(){
		if(!$this->orm){
			return null;
		}else if($this->orm instanceof Model){
			return $this->orm->userRela->count();
		}else if($this->orm instanceof Collection){
			$result = [];
			foreach ($this->orm as $orm){
				$result[$orm->foreign_id] = $orm->userRela->count();
			}
			return $result;
		} 
		return null;
	}
	
	/**
	 * 获取用户
	 */
	public function getUserInfo(){
		$orgIds = [];
		if(!$this->orm){
			return null;
		}else if($this->orm instanceof Model){
			$orgIds[] = $this->orm->foreign_id;
		}else if($this->orm instanceof Collection){
			$result = [];
			foreach ($this->orm as $orm){
				$orgIds[] = $orm->foreign_id;
			}
		}	
		if(empty($orgIds)){
			return null;
		}
		$userQuery = new UserQuery();
		$userData = $userQuery->getForOrgIds($orgIds);
		return $userData->getDetail(['user_id', 'contact_name']);
	}
	
	/**
	 * 获取父部门数据
	 * @param array $column 限定字段 为空不限定字段
	 */
	public function getPData($column=[]){
		if(!$this->orm){
			return null;
		}else if($this->orm instanceof Model && ($tmp=$this->orm->getForForginId($this->orm->pid))){
			if(!empty($column)){
				$result = array_only($tmp->toArray(), $column);
			}else{
				$result = $tmp->toArray();				
			}		
			return $result;
		}else if($this->orm instanceof Collection){
			$result = [];
			foreach ($this->orm as $orm){
				if(($tmp = $orm->getForPid($orm->pid))){
					if(!empty($column)){
						$result[$orm->foreign_id] = array_only($tmp->toArray(), $column);
					}else{
						$result[$orm->foreign_id] = $tmp->toArray();
					}
				}
			}
			return $result;
		}
		return null;		
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
		if(in_array('usernum', $column)){
			$result = $this->appendUserNum($result);
		}
		if(in_array('pname', $column)){
			$result = $this->appendPName($result);
		}
		
		return $result;
	}
	
	/**
	 * 树形数据
	 * @param array $column 限定字段 为空不限定字段
	 */
	public function treeData($column=[]){
		if($this->orm instanceof Collection){
			return $this->recuTree($this->orm->toArray(), $column);
		}
		return null;
	}
	
	/**
	 * 基础数据追加用户数量
	 */
	protected function appendUserNum($result){
		$userNum = $this->getUserNum();
		foreach ($result as $k=>$row){
			if(is_array($row)){
				$result[$k]['usernum'] = ($userNum && isset($userNum[$row['foreign_id']])) ?  $userNum[$row['foreign_id']]: 0;
			}else{
				$result['usernum'] = $userNum ? $userNum : 0;
			}
		}
		return $result;
	}
	
	/**
	 * 基础数据追加父部门名称
	 */
	protected function appendPName($result){
		$pNames = $this->getPData(['name']);		
		foreach($result as $k=>$row){
			if(is_array($row)){
				$result[$k]['pname'] = ($pNames && isset($pNames[$row['pid']])) ?  $pNames[$row['pid']]['name']: '一级部门';
			}else{
				$result['pname'] = $pNames['name'] ? $pNames['name'] : '一级部门';
			}
		}
		return $result;
	}
	
	/**
	 * 平级数据到树形结构
	 * @param array $datas
	 * @param int $pid
	 */
	protected function recuTree($datas, $colnum=[], $pid=0){
		$result = [];
		foreach ($datas as $k=>$row){
			if($row['pid'] != $pid){
				continue;
			}
			if(empty($colnum)){
				$result[$k] = $row;
			}else{
				$result[$k] = array_only($row, $colnum);
			}
			$result[$k]['id'] = $row['foreign_id'];
			$result[$k]['children'] = $this->recuTree($datas, $colnum, $row['foreign_id']);
		}
		return array_values($result);		
	}
}
