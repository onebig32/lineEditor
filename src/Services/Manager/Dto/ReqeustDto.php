<?php
namespace Line/Services/Manager/Dto;

use Illuminate\Http\Request;

/**
 * request dto对象
 */

class RequestDto{
	private $request;
	public function __contruct(Request $request){
		$this->request = $request;
	}

	public function createData(){
		$data = $this->request->all();

		// 逻辑处理

		return $data;
	}
}