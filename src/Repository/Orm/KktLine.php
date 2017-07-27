<?php
namespace Line/Repository/Orm;

class KktLine extends MModel(){
	
	/**
	 * 批数据处理
	 */
	public function listQuery(QueryWhereDto $dto, $page, $limit){

			return $this->get();
	}

}