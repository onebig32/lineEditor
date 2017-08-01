<?php
namespace Line\Services\Manager\Dto;

use Illuminate\Http\Request;
/**
 * 构建实体 dto对象
 */

class CreateEntityDto{
	private $createEntityDto;
	public function __construct($createDto = []){
		$this->createEntityDto = $createDto;
	}

	public function getItemData(){
		$data = $this->createEntityDto;
		$itemArray = [];
		foreach($data['tours'] as $tourKey=>$row){
			$itemArray[$tourKey] =[];
			foreach($row['items'] as $item){
				$itemArray[$tourKey][] = $item;
			}
		}
		return $itemArray;
	}

	public function getTourData(){
		$data = $this->createEntityDto;
		$tourArray = [];
		foreach($data['tours'] as $key=>$row){
			$tourArray[] = ['is_delete'=>1];
		}
		return $tourArray;
	}

	public function getLineData(){
		$data = $this->createEntityDto;
		$baseArray = [
			'title'=>$data['line']['title'],
			'day_num'=>$data['line']['day_num'],
		];
		$dayArray = [];
		foreach($data['tours'] as $key=>$row){
			$dayArray[$key] =[
				'day'=>$row['day'],
				'title'=>$row['title'],
			];
		}
		$lineArray = [
			'line'=>$baseArray,
			'days'=>$dayArray
		];
		return $lineArray;
	}
}