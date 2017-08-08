<?php
namespace Line\Contact\ReplyDto;

abstract class AbstractHttpDto{
	/**
	 * 所有的错误通过异常抛出
	 * @param array $data
	 */
	public function successHttpResponse($data=[]){
		$data = array(
				'code' => '2000',
				'msg' => 'success',
				'data' => $data,
		);
		return response()->json($data);
	}
}
