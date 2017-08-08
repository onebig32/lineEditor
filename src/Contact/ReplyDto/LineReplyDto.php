<?php
namespace Line\Contact\ReplyDto;

class LineReplyDto extends AbstractHttpDto
{
    /**
     * 返回列表数据
     * @param array $data 线路数据
     * @param array $fieldArray 显示字段数组
     */
    public function tableList($data = [], $fieldArray = [])
    {
        $result = $data;
        $result['data'] = [];
        $keys = [
            'dest_city_pid', 'title', 'day_num', 'cover_url',
            'cover_group', 'cover_img_id', 'tours','cover_url_'
        ];
        foreach ($data['data'] as $k => $obj) {
            $array = $obj->baseData($keys);
            //过滤之后的字段
            if ($fieldArray) {
                $array = array_only($array, $fieldArray);
            }
            $result['data'][$k] = $array;
        }
        return $this->successHttpResponse($result);
    }

    /**
     * 返回数据
     */
    public function response($data = [])
    {
        return $this->successHttpResponse($data);
    }

}