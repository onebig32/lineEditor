<?php
namespace Line\Contact\Rules;
use App\Http\Requests\Request;

class LineRules extends Request{
	protected $identifyMethod = true;
	
	public function getRules(){
		$array = [
			'listajax' => $this->listAjax(),
			'getoneajax' => $this->getOneAjax(),
			'addajax' => $this->addAjax(),
			'updateajax' => $this->updateAjax(),
			'deleteajax' => $this->deleteAjax(),
			'copyajax' => $this->copyAjax()
		];
		return $array;
	}
	
	protected function listAjax(){
		return [
			'fields' => 'required|string',
			'limit' => 'integer|min:1',
			'page' => 'integer|min:1',
			'dayNum' => 'integer|min:1',
			'destCityId' => 'integer',
			'startDate' => 'date',
			'endDate' => 'date',
			'searchKey'=> 'string',
		];
	}
	
	protected function addAjax(){
		return [
			'title' => 'required|string',
			'is_draft' => 'required|integer|in:0,1',
			'tours' => 'required|array',
			'tours.*.day' => 'required|integer',
			'tours.*.title' => 'required|string',
			'tours.*.items' => 'required|array',
			'tours.*.items.*.title' => 'required|string',
			'tours.*.items.*.desc' => 'string',
			'tours.*.items.*.type_id' => 'required|integer',
			'tours.*.items.*.time_type' => 'required|integer',
			'tours.*.items.*.time' => 'required|numeric',
			'tours.*.items.*.is_import' => 'required|integer|in:0,1',
			'tours.*.items.*.self_care' => 'integer|in:0,1',
			'tours.*.items.*.dinning_type' => 'integer|in:1,2,3',
			'tours.*.items.*.dest_city_id' => 'integer',
			'tours.*.items.*.traffic_type' => 'integer|in:1,2,3,4,5,6,7',
			'tours.*.items.*.distance_type' => 'integer|in:1,2,3',
			'tours.*.items.*.distance' => 'numeric',
			'tours.*.items.*.imgs' => 'array',
			'tours.*.items.*.imgs.*.cover' => 'integer|in:0,1',
			'tours.*.items.*.imgs.*.large_url' => 'string',
			'tours.*.items.*.imgs.*.middle_url' => 'string',
			'tours.*.items.*.imgs.*.small_url' => 'string',
			'tours.*.items.*.imgs.*.group_large' => 'string',
			'tours.*.items.*.imgs.*.group_middle' => 'string',
			'tours.*.items.*.imgs.*.group_small' => 'string',
		];
	}

	protected function updateAjax(){
		return [
				'title' => 'required|array',
				'tours' => 'required|array',
				'tours.*.day' => 'required|integer',
				'tours.*.title' => 'required|string',
				'tours.*.item' => 'required|array',
				'tours.*.item.*.id' => 'required|integer',
				'tours.*.item.*.title' => 'required|string',
				'tours.*.item.*.desc' => 'string',
				'tours.*.item.*.type_id' => 'required|integer',
				'tours.*.item.*.time_type' => 'required|integer',
				'tours.*.item.*.time' => 'required|numeric',
				'tours.*.item.*.is_import' => 'required|integer|in:0,1',
				'tours.*.item.*.self_care' => 'integer|in:0,1',
				'tours.*.item.*.dinning_type' => 'integer|in:1,2,3',
				'tours.*.item.*.dest_city_id' => 'integer',
				'tours.*.item.*.traffic_type' => 'integer|in:1,2,3,4,5,6,7',
				'tours.*.item.*.distance_type' => 'integer|in:1,2,3',
				'tours.*.item.*.distance' => 'numeric',
		];
	}

	protected function getOneAjax(){
		return [
				'lineId' => 'required|integer|min:1'
		];
	}
	
	protected function deleteAjax(){
		return [
			'lineId' => 'required|integer|min:1'
		];
	}
	
	protected function copyAjax(){
		return [
			'lineId' => 'required|integer|min:1'
		];
	}
}
