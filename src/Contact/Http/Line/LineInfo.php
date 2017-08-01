<?php
namespace Line\Contact\Http\Line;

use App\Http\Controllers\Controller;
use Line\Contact\Rules\LineRules;
use Line\Services\Manager\LineManager;
use Line\Services\Manager\Dto\RequestDto;

class LineInfo extends Controller{

	/**
	 * 批量数据获取
	 */
	public function listAjax(){

	}

	/**
	 * 单条明细数据
	 */
	public function getOneAjax(){

	}

	/**
	 * 创建线路
	 */
	public function addAjax(LineRules $request){
		$lineId = (new LineManager())->createLine(new RequestDto($request));
		$this->successJsonResponse([$lineId]);
	}

	/**
	 * 更新
	 */
	public function updateAjax(){

	}

	/**
	 * 删除线路
	 */
	public function deleteAjax(){

	}

	/**
	 * 复制线路
	 */
	public function copyAjax(){

	}




}