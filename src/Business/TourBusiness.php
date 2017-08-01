<?php
namespace Line\Business;

use Line\Business\Exceptions\LineException;
use Line\Repository\Entity\LineEntity;
use Line\Repository\Entity\TourEntity;

class TourBusiness {
	/**
	 * 创建线路业务逻辑
	 * @param dto $lineEntity
	 * @param dto $tourEntity
	 * @return array (
			0 =>$lineEntity 线路实体
	 * 		1 =>$tourEntity 行程实体
	 * )
	 */
	public function createLine($data = []){
		//线路实体
		$lineEntity = new LineEntity();
		$lineEntity->arrayToProp($data['line']);

		return $lineEntity;

	}

	/**
	 * 编辑线路
	 * (1) 正常
	 * (2) 线路存在，行程不存在，资源存在
	 * (3) 线路，行程，资源不匹配（。。。）
	 */
	public function editLine(LineEntity $lineEntity){
		if(!$lineEntity){
			throw new LineException('线路不存在','001');
		}
		if(!$tourEntity){
			throw new LineException('行程不存在','002');
		}
		return [$lineEntity, $tourEntity];
	}
}