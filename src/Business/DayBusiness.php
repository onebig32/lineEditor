<?php
namespace Line\Business;

use Line\Business\Exceptions\LineException;
use Line\Repository\Entity\DayEntity;

class DayBusiness {
	/**
	 * 创建线路天数
	 * )
	 */
	public function createDay(DayEntity $dayEntity){
		//线路天数实体
		if(!$dayEntity){
			throw new LineException('线路天数不存在','002');
		}
		return $dayEntity;
	}
}