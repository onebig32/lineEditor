<?php
namespace Line/Services/Query;

class LineQuery{
	private $lineOrm;

	public function __contruct(){
		$this->lineOrm = new KktLine();
	}

	/**
	 * 批处理查询
	 * @param array $where
	 */
	public function listQuery($where, $page, $limit){
		$whereDto = new QueryWhereDto($where);

		$result = $this->lineOrm->listQuery($whereDto, $page, $limit);

		return new QueryResultDto($result);
	}

}