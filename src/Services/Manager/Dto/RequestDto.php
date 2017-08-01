<?php
namespace Line\Services\Manager\Dto;

use Illuminate\Http\Request;

/**
 * request dto对象
 */

class RequestDto{
	private $request;
	public function __construct(Request $request){
		$this->request = $request;
	}

	/**
	 * 获取构建数据dto
	 */
	public function getCreateDto(){
		$data = $this->request->all();
		$resDto = [
			'line'=>[
				'title'=>$data['title'],
				'day_num'=>count($data['tours'])
			],
			'tours'=>$data['tours']
		];
		return $resDto;
	}

	public function getTourData(){

	}

	public function getLineData(){

	}
}