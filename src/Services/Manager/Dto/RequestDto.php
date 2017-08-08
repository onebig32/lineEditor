<?php
namespace Line\Services\Manager\Dto;

use Illuminate\Http\Request;
use Line\Business\Exceptions\ParamException;

/**
 * request dto对象
 */
class RequestDto
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 获取参数数据构建数据dto
     */
    public function getCreateDto()
    {
        $data = $this->request->all();
        $destCity = [];
        foreach ($data['tours'] as &$row) {
            $row['is_delete'] = isset($row['is_delete']) && $row['is_delete'] ? 2 : 1;
            foreach ($row['items'] as &$item) {
                $item['is_delete'] = isset($item['is_delete']) && $item['is_delete'] ? 2 : 1;
                if ($item['type_id'] == 7) {
                    //目的国
                    array_push($destCity, $item['dest_city_id']);
                }
                //封面图
                $coverArray = ['cover_url' => '', 'cover_group' => ''];
                if (isset($item['imgs']) && $item['imgs']) {
                    foreach ($item['imgs'] as &$img) {
                        $img['is_delete'] = isset($img['is_delete']) && $img['is_delete'] ? 2 : 1;
                        if ($img['cover'] == 1) {
                            $coverArray = [
                                'cover_url' => $img['middle_url'],
                                'cover_group' => $img['group_middle']
                            ];
                        }
                    }
                }
            }
        }
        //线路目的国
        if (!$destCity) {
            throw new ParamException('参数异常，缺少目的国', '001');
        } else if (count(array_unique($destCity)) == 1) {
            $destCityPid = $destCity[0];
        } else {
            $destCityPid = 38;
        }
        $resDto = [
            'line' => [
                'id' => isset($data['id']) ? $data['id'] : null,
                'title' => $data['title'],
                'day_num' => count($data['tours']),
                'dest_city_pid' => $destCityPid,
                'cover_url' => $coverArray['cover_url'],
                'cover_group' => $coverArray['cover_group']
            ],
            'tours' => $data['tours']
        ];
        return $resDto;
    }

    /**
     * 获取构建列表查询条件DTO
     */
    public function getListWh()
    {
        $data = $this->request->all();
        if (isset($data['searchKey']) && $data['searchKey']) {
            $where['title'] = $data['searchKey'];
        }
        if (isset($data['dayNum']) && $data['dayNum']) {
            $where['day_num'] = $data['dayNum'];
        }
        if (isset($data['destCityId']) && $data['destCityId']) {
            $where['dest_city_pid'] = $data['destCityId'];
        }
        if (isset($data['draft']) && $data['draft']) {
            $where['draft'] = $data['draft'];
        }
        return $data;
    }

    /**
     * 获取构建数据DTO
     */
    public function getPage()
    {
        $page = $this->request->input('page', 1);
        return $page;
    }

    /**
     * 获取每页限制限制条数
     */
    public function getLimit()
    {
        $limit = $this->request->input('limit', 14);
        return $limit;
    }

    /**
     * 获取id
     */
    public function getId()
    {
        $id = $this->request->input('lineId');
        if ($id) {
            return $id;
        }
        return null;
    }

    /**
     * 获取显示字段
     */
    public function getField(){
        $fieldArray = [];
        if($fieldStr = $this->request->input('fields')){
            $fieldArray = explode(',',$fieldStr);
        }
        return $fieldArray;
    }
}