<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use Line\Repository\Entity;
use Line\Repository\Entity\ImgEntity;
use Line\Services\Query\Img\QueryWhereDto;

class KktLineImg extends MModel{
	public $timestamps = true;
	protected $table = 'kkt_line_img';
	protected $fillable = [
		'large_url', 'middle_url','small_url','group_large','group_middle','group_small','is_delete', 'created_at', 'updated_at'
	];
	protected $connection = "mysql_platform";

	/**
	 * 保存单项资源实体对象
	 */
	public function saveEntity(ImgEntity $imgEntity){

		$data = $imgEntity->toArray();
		if ($imgEntity->id && ($orm = $this->find($imgEntity->id))) {
			$orm->fill($data)->save();
		} else {
			$orm = $this->create($data);
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
				->first();
		if(!$orm){
			return null;
		}
		return new ImgEntity($orm);
	}


}
