<?php
namespace Line\Contact\Http;

use App\Http\Controllers\Controller;

class Line extends Controller{
	/**
	 * 创建线路
	 */
	public function createLine(LineRuels $request){
		$lineManager = new lineManager();
		$lineId = $lineManager->createLine(new RequestDto($request));

		$this->successJson($lineId);
	}

	/**
	 * 删除线路
	 */
	public function deleteLine(){

	}

	/**
	 * copy
	 */
	public function copy(){

	}

	/**
	 * 更新
	 */
	public function update(){

	}

	/**
	 * 批量数据获取
	 */
	public function listData(){

	}

	/**
	 * 单条明细数据
	 */ 
	public function detail(){

	}
}