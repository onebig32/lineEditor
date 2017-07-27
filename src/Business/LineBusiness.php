<?php
namespace Line/Business;

class LineBusiness {
	/**
	 * 创建线路业务逻辑
	 * @param array $data
	 *
	 * (1) 正常的
	 *  
	 */
	public function createLine(array $data){
		// 构造线路实体

		// 构造行程实体

		// 资源实体

		return [$lineEntity, $tourEntity, ];

	}

	/**
	 * 编辑线路
	 * (1) 正常
	 * (2) 线路存在，行程不存在，资源存在
	 * (3) 线路，行程，资源不匹配（。。。）
	 */
	public function eidtLine(LineEntity $lineEntiy, $tourEntity, ){
		if(!$lineEntity){
			throw new  
		}	
		return [$lineEntity, $tourEntity, ];
	}
}