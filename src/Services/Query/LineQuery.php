<?php
namespace Line\Services\Query;

use Line\Repository\Orm\KktLine;

class LineQuery{
	private $lineOrm;

	public function __construct(){
		$this->lineOrm = new KktLine();
	}

	/**
	 * 批处理查询
	 * @param array $where
	 */
	public function listQuery($where, $page, $limit){
		$whereDto = new QueryWhereDto($where);

		$result = $this->lineOrm->tableQuery($whereDto, $page, $limit);

		return new QueryResultDto($result);
	}

}