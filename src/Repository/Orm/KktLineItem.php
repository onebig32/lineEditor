<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use Line\Repository\Entity\itemEntity;
use DB;
use Line\Services\Query\Item\QueryWhereDto;

class KktLineItem extends MModel{
	public $timestamps = true;
	protected $table = 'kkt_line_item';
	protected $fillable = [
		'type_id', 'title', 'desc','time_type','time','is_delete', 'created_at', 'updated_at','type_name'
	];
	protected $connection = "mysql_platform";
	
	/**
	 * 定义与单项资源关系
	 */
	public function imgs(){
		return $this->hasMany('Line\Repository\Orm\KktLineItemImgs', 'item_id');
	}
	
	/**
	 * 保存单项资源实体对象
	 */
	public function saveEntity(itemEntity $itemEntity){

		$data = $itemEntity->toArray();
		if ($itemEntity->id && ($orm = $this->find($itemEntity->id))) {
			$orm->fill($data)->save();
		} else {
			$orm = $this->create($data);
		}
		//保存单项资源图片关系
		$orm->imgs()->update(['is_delete'=>2]);
		if(isset($data['img_ids']) && $data['img_ids']){
			$itemImgModel = new KktLineItemImgs();
			if(isset($data['img_ids'])){
				foreach($data['img_ids'] as $imgId){
					if($imgId && ($itemImgOrm = $itemImgModel->find($imgId))){
						$itemImgOrm->is_delete = 1;
						$itemImgOrm->save();
					}else{
						$orm->imgs()->save(new KktLineItemImgs(['img_id'=>$imgId]));
					}
				}
			}
		}
		return $orm->id;
	}

	/**
	 * 多数据复合条件查询
	 */
	public function tableQuery(QueryWhereDto $dto, $page, $limit)
	{
		$result = ['data' => [], 'page' => $page, 'limit' => $limit, 'count' => 0];
		$model = $this->where('is_delete', 1);
		foreach ($dto->wh() as $row) {
			if($row[1]=='in'){
				$model = $model->whereIn($row[0], $row[2]);
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
				->with(['imgs'=>function($query){
					$query->where('is_delete',1);
				}])
				->first();
		if(!$orm){
			return null;
		}
		return new itemEntity($orm);
	}


	/**
	 * 根据id查询获取orm
	 */
	public function getOrmForId($id){
		return $this->where('id', $id)
				->where('is_delete', 1)
				->first();
	}
	
}
