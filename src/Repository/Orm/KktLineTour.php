<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use Line\Repository\Entity\TourEntity;
use Exception;
use Line\Services\Query\QueryWhereDto;
use DB;

class KktLineTour extends MModel{
	public $timestamps = true;
	protected $table = 'kkt_line_tour';
	protected $fillable = [
		'id', 'is_delete', 'created_at', 'updated_at'
	];
	protected $connection = "mysql_platform";
	
	/**
	 * 定义与单项资源关系
	 */
	public function items(){
		return $this->hasMany('Line\Repository\Orm\KktLineTourItem', 'tour_id');
	}
	
	/**
	 * 保存实体对象
	 */
	public function saveEntity(TourEntity $tourEntity){
		$data = $tourEntity->toArray();
		$itemIds = $data['item_ids'];
		unset($data['item_ids']);
		if ($tourEntity->id && ($orm = $this->find($tourEntity->id))) {
			$orm->fill($data)->save();
		} else {
			$orm = $this->create($data);
		}
		if($itemIds){
			$tourItemModel = new KktLineTourItem();
			$tourItemModel->where('tour_id',$orm->id)->update(['is_delete'=>2]);
			foreach($itemIds as $key=>$itemId){
				$tmp = $tourItemModel
						->where('tour_id',$orm->id)
						->where('item_id',$itemId)
						->first();
				if($tmp){
					$tmp->is_delete = 1;
					$tmp->sort = $key;
					$tmp->save();
				}else{
					$createData = [
						'tour_id'=>$orm->id,
						'item_id'=>$itemId,
						'sort'=>$key
					];
					$orm = $tourItemModel->create($createData);
				}
			}
		}
		return $orm->id;

	}

	
	/**
	 * 多数据复合条件查询
	 */
	public function tableQuery(QueryWhereDto $dto, $page, $limit){
		$result = ['data'=>[], 'page'=>$page, 'limit'=>$limit, 'count'=>0];
		$model = $this->where('is_delete', 1)->where('is_history', 1);
		foreach($dto->wh() as $row){
			$model = $model->where($row[0], $row[1], $row[2]);
		}
		$orderBy = $dto->orderBy();
		$result['count'] = $model->count();
		$result['data'] = $model->orderBy($orderBy['value'], $orderBy['type'])->limit($limit)->offset(($page-1)*$limit)->get();
		return $result;
	}
	
}
