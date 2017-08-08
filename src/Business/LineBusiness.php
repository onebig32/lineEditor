<?php
namespace Line\Business;

use Line\Business\Exceptions\LineException;
use Line\Repository\Entity\LineEntity;
use Line\Services\Query\LineQuery;

class LineBusiness {
	/**
	 * 创建线路业务逻辑
	 * @param Line\Repository\Entity\LineEntity $lineEntity 线路实体对象
	 * @return array $lineEntity 线路实体

	 */
	public function createLine(LineEntity $lineEntity){
		//逻辑判断
		return $lineEntity;

	}

	/**
	 * 编辑线路业务逻辑
	 * @param Line\Repository\Entity\LineEntity $lineEntity 线路实体对象
	 * @return array $lineEntity 线路实体
	 */
	public function editLine(LineEntity $lineEntity){
		if(!$lineEntity){
			throw new LineException('线路不存在','001');
		}
		//业务逻辑
		return $lineEntity;
	}

	/**
	 * 复制线路业务逻辑
	 * @param Line\Repository\Entity\LineEntity $lineEntity 线路实体对象
	 * @return array $lineEntity 线路实体
	 */
	public function copyLine(LineEntity $lineEntity){
		if(!$lineEntity){
			throw new LineException('线路不存在','001');
		}
		return $lineEntity;
	}

	/**
	 * 删除业务逻辑
	 * @param Line\Repository\Entity\LineEntity $lineEntity 线路实体对象
	 * @return array $lineEntity 线路实体
	 */
	public function deleteLine(LineEntity $lineEntity){
		if(!$lineEntity){
			throw new LineException('线路不存在','001');
		}
		$lineEntity->is_delete = 2;
		return $lineEntity;
	}

}