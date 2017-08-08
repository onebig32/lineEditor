<?php
namespace Line\Services\Query\Item;

use Line\Repository\Orm\KktLineItem;

class ItemQuery{
	private $itemOrm;

	public function __construct(){
		$this->itemOrm = new KktLineItem();
	}

	/**
	 * 批处理查询
	 * @param array $where
	 */
	public function listQuery($where, $page, $limit){
		$data = $result = $this->itemOrm->tableQuery(new QueryWhereDto($where), $page, $limit);
		$result['data'] = [];
		foreach($data['data'] as $key=>$orm){
			$result['data'][$key] = new QueryResultDto($orm);
		}
		return $result;
	}


	/**
	 * 根据id查询
	 */
	public function getOneForId($id){
		return new QueryResultDto($this->itemOrm->getOrmForId($id));
	}





}