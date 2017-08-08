<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use DB;
use Line\Repository\Entity\DayEntity;
use Line\Services\Query\Day\QueryWhereDto;

class KktLineDay extends MModel{
	public $timestamps = true;
	protected $table = 'kkt_line_day';
	protected $fillable = [
		'line_id', 'day', 'is_delete', 'created_at', 'updated_at'
	];
	protected $connection = "mysql_platform";

	/**
	 * 定义与单项资源关系
	 */
	public function items(){
		return $this->hasMany('Line\Repository\Orm\KktLineDayItems', 'day_id');
	}

	/**
	 * 保存实体对象
	 */
	public function saveEntity(DayEntity $dayEntity)
	{
		$data = $dayEntity->toArray();
		$importArray = $data['import_arrays'];
		if ($dayEntity->id && ($orm = $this->find($dayEntity->id))) {
			$orm->fill($data)->save();
		} else {
			$orm = $this->create($data);
		}
		//保存单项资源图片
		$orm->items()->update(['is_delete'=>2]);
		if(isset($data['item_ids']) && $data['item_ids']){
			$datItemModel = new KktLineDayItems();
			foreach($data['item_ids'] as $key=>$itemId){
				if(isset($itemId) && $itemId && ($dayItemOrm = $datItemModel->find($itemId))){
					$dayItemOrm->is_delete = 1;
					$dayItemOrm->sort = $key;
					$dayItemOrm->is_import = $importArray[$key];
					$dayItemOrm->save();
				}else{
					$orm->items()->save(new KktLineDayItems(['item_id'=>$itemId,'sort'=>$key,'is_import'=>$importArray[$key]]));
				}
			}
		}
		return $orm->id;
	}

	/**
	 * 多数据复合条件查询
	 */
	public function tableQuery(QueryWhereDto $dto, $page = 1, $limit = 14)
	{
		$result = ['data' => [], 'page' => $page, 'limit' => $limit, 'count' => 0];
		$model = $this->where('is_delete', 1);

		foreach ($dto->wh() as $row) {
			if($row[1] == 'in'){
				$model = $model->whereIn($row[0],$row[2]);
			}else{
				$model = $model->where($row[0], $row[1], $row[2]);
			}
		}
		foreach($dto->relation() as $relation){
			$model = $model->with([$relation=>function ($query) {
				$query->where('is_delete', 1);
			}]);
		}
		$orderBy = $dto->orderBy();
		$result['count'] = $model->count();
		$result['data'] = $model->orderBy($orderBy[0], $orderBy[1])->limit($limit)->offset(($page - 1) * $limit)->get();
		return $result;
	}

	/**
	 * 获取一个存在的实体
	 */
	public function getExistEntity($primaryKeyValue){
		$orm = $this->where('id', $primaryKeyValue)
				->where('is_delete', 1)
				->with(['items'=>function($query){
					$query->where('is_delete',1);
				}])
				->first();
		if(!$orm){
			return null;
		}
		return new DayEntity($orm);
	}




	
}
