<?php
namespace Lien/Services;

Line/Services/Manager/Dto/RequestDto;

/**
 * 线路管理服务
 */

class LineManager {
	/**
	 * 创建线路
	 */
	public function createLine(RequestDto $dto){
		$lineBusiness = new businessLine();

		$result = $lineBusiness->createLine($dto->createData());

		// 启用事务
		$lienId = $lineOrm->saveEntity($result[0]);

		$tourOrm->saveEntity($result[1]);

		$resoruOrm->saveEntity($result[1]);

		return $lienId;
	}

	/**
	 * 删除线路
	 */
	public function deleteLine(){

	}

	/**
	 * copey
	 */
	public function copyLine(){

	}

	/**
	 * 编辑线路
	 */
	public function editLine(){
		$entity = $lineOrm -> getEntity($id);		// 创建实体
	}
}