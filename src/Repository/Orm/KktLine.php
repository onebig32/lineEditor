<?php
namespace Line\Repository\Orm;

use App\Models\MModel;
use Line\Repository\Entity\LineEntity;
use Exception;
use Line\Services\Query\QueryWhereDto;
use DB;

class KktLine extends MModel
{
    public $timestamps = true;
    protected $table = 'kkt_line';
    protected $fillable = [
        'title', 'day_num', 'uuid', 'created_user_id', 'created_organization_id', 'is_delete', 'created_at', 'updated_at', 'is_draft'
    ];
    protected $connection = "mysql_platform";

    /**
     * 定义与单项资源关系
     */
    public function days()
    {
        return $this->hasMany('Line\Repository\Orm\KktLineDay', 'line_id');
    }

    /**
     * 保存实体对象
     */
    public function saveEntity(LineEntity $lineEntity)
    {
        $data = $lineEntity->toArray();
        $lineData = $data['line'];
        $dayData = $data['days'];
        $tourIds = $data['tour_ids'];
        if (!empty($lineData['id'])&& ($orm = $this->find($lineData['id']))) {
            $orm->fill($lineData)->save();
        } else {
            $orm = $this->create($lineData);
        }
        $dayModel = new KktLineDay();
        $dayModel->where('line_id')->update(['is_delete'=>2]);
        foreach($tourIds as $key=>$tourId){
            $tmp = $dayModel
                ->where('line_id',$orm->id)
                ->where('tour_id',$tourId)
                ->first();
            if($tmp){
                $tmp->is_delete = 1;
                $tmp->save();
            }else{
                $createData = [
                    'line_id'=>$orm->id,
                    'tour_id'=>$tourId,
                    'day'=>$dayData[$key]['day'],
                    'title'=>$dayData[$key]['title']
                ];
                $orm = $dayModel->create($createData);
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
        foreach ($dto->getLineWh() as $row) {
            $model = $model->where($row[0], $row[1], $row[2]);
        }
        $orderBy = $dto->orderBy();
        $result['count'] = $model->count();
        $result['data'] = $model->orderBy($orderBy['value'], $orderBy['type'])->limit($limit)->offset(($page - 1) * $limit)->get();
        return $result;
    }

}
