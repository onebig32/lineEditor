<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use Line\Repository\Entity\LineEntity;
use Line\Services\Query\Line\QueryWhereDto;
use DB;

class KktLine extends MModel
{
    public $timestamps = true;
    protected $table = 'kkt_line';
    protected $fillable = [
        'title', 'day_num', 'uuid', 'created_user_id', 'created_organization_id', 'is_delete', 'created_at', 'updated_at',
        'is_draft','cover_url','cover_group','cover_img_id','dest_city_pid','dest_city_name'
    ];
    protected $connection = "mysql_platform";

    /**
     * 定义与线路天数关系
     */
    public function days()
    {
        return $this->hasMany('Line\Repository\Orm\KktLineDayRela', 'line_id');
    }

    /**
     * 保存实体对象
     */
    public function saveEntity(LineEntity $lineEntity)
    {
        $data = $lineEntity->toArray();
        if ($lineEntity->id && ($orm = $this->find($lineEntity->id))) {
            $orm->fill($data)->save();
        } else {
            $orm = $this->create($data);
        }
        //保存单项资源图片
        $orm->days()->update(['is_delete'=>2]);
        if(isset($data['day_ids']) && $data['day_ids']){
            $dayRelaModel = new KktLineDayRela();
            foreach($data['day_ids'] as $dayId){
                $dayRelaOrm = $dayRelaModel->where('line_id',$orm->id)->where('day_id',$dayId)->first();
                if($dayRelaOrm){
                    $dayRelaOrm->is_delete = 1;
                    $dayRelaOrm->save();
                }else{
                    $orm->days()->save(new KktLineDayRela(['day_id'=>$dayId]));
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
     * 根据id查询获取orm
     */
    public function getOrmForId($id){
        return $this->where('id', $id)
            ->where('is_delete', 1)
            ->first();
    }

    /**
     * 获取一个存在的实体
     */
    public function getExistEntity($primaryKeyValue){
        $orm = $this->where('id', $primaryKeyValue)
            ->where('is_delete', 1)
            ->with(['days'=>function($query){
                $query->where('is_delete',1);
            }])
            ->first();
        if(!$orm){
            return null;
        }
        return new LineEntity($orm);
    }

    /**
     * 获取封面图片地址
     */
    public function getCoverUrl(){
        return  $this->cover_img_id ? app('Fastdfs')->localToUrl($this->cover_group, $this->cover_url):'';
    }

}
