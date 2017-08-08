<?php
namespace Line\Services\Query\Day;

use Line\Repository\Orm\KktLineDay;

class DayQuery{
	private $dayOrm;

	public function __construct(){
		$this->dayOrm = new KktLineDay();
	}

	/**
	 * 批处理查询
	 * @param array $where
	 */
	public function listQuery($where, $page, $limit){
		$data = $result = $this->dayOrm->tableQuery(new QueryWhereDto($where), $page, $limit);
		$result['data'] = [];
		foreach($data['data'] as $key=>$orm ){
			$result['data'][$key] = new QueryResultDto($orm);
		}
		return $result;
	}



	/**
	 * 根据id查询
	 */
	public function getOneForId($id = 0 ){
		return new QueryResultDto($this->dayOrm->getOrmForId($id));
	}




}