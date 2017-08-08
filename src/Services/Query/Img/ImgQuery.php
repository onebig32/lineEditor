<?php
namespace Line\Services\Query\Img;

use Line\Repository\Orm\KktLineImg;

class ImgQuery{
	private $imgOrm;

	public function __construct(){
		$this->imgOrm = new KktLineImg();
	}

	/**
	 * 批处理查询
	 * @param array $where
	 */
	public function listQuery($where, $page, $limit){
		$data = $result = $this->imgOrm->tableQuery(new QueryWhereDto($where), $page, $limit);
		$result['data'] = [];
		foreach($data['data'] as $key=>$orm ){
			$result['data'][$key] = new QueryResultDto($orm);
		}
		return $result;
	}



}