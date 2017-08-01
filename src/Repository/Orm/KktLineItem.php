<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use Line\Repository\Entity\itemEntity;
use DB;

class KktLineItem extends MModel{
	public $timestamps = true;
	protected $table = 'kkt_line_item';
	protected $fillable = [
		'type_id', 'title', 'desc','time_type','time','is_import','is_delete', 'created_at', 'updated_at','type_name'
	];
	protected $connection = "mysql_platform";
	
	/**
	 * 定义与单项资源关系
	 */
	public function imgs(){
		return $this->hasMany('Line\Repository\Orm\KktLineItemImg', 'item_id');
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
		//保存单项资源图片
		$orm->imgs()->update(['is_delete'=>2]);
		if(isset($data['imgs']) && $data['imgs']){
			foreach($data['imgs'] as $imgRow){
				$orm->imgs()->save(new KktLineItemImg($imgRow));
			}
		}
		return $orm->id;

	}
	
}
