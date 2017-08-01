<?php
namespace Line\Contact\ReplyDto;

use Line\Contact\ReplyDto\AbstractHttpDto;

class LineReplyDto extends AbstractHttpDto{
	/**
	 * 列表数据
	 * @param array $data
	 */
	public function tableList($data){
		$result = $data;
		$result['data'] = [];
		$keys = [
			'foreign_id', 'id',	'name', 'pid',
			'pname', 'updated_at', 'usernum'		
		];
		foreach($data['data'] as $k=>$obj){
			$result['data'][$k] = $obj->baseData($keys);
		}
		return $this->successHttpResponse($result);
	}
	
	/**
	 * 树形结构数据
	 */
	public function treeList($data){
		$keys = ['id', 'name'];
		$result = $data->treeData($keys);
		return $this->successHttpResponse($result);
	}
	
	/**
	 * 包含员工的数据
	 */
	public function hasUserData($data){
		$result = $data;
		$result['data'] = [];

		foreach($data['data'] as $k=>$obj){
			$baseData = $obj->baseData(['foreign_id', 'name']);
			$userInfo = $obj->getUserInfo();
			$result['data'][$k]['type'] = 1;
			$result['data'][$k]['id'] = $baseData ? $baseData['foreign_id']: 0;
			$result['data'][$k]['name'] = $baseData ? $baseData['name']: '';
			$result['data'][$k]['children'] = [];
			if($userInfo){
				$tmp = [];
				foreach ($userInfo as $kkey=>$row){
					$tmp[$kkey]['type'] = 2;
					$tmp[$kkey]['name'] = $row['contact_name'];
					$tmp[$kkey]['id'] = $row['user_id'];
				}
				$result['data'][$k]['children'] = array_values($tmp);
			}
		}
		$result['data'] = array_values($result['data']);
		return $this->successHttpResponse($result);
	}
}