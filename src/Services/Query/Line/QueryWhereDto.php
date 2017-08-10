<?php
namespace Line\Services\Query\Line;

class QueryWhereDto
{
    private $whereParams;

    public function __construct(array $where)
    {
        $this->whereParams = $where;
    }

    /**
     * 构建基础查询（针对Kktline）
     */
    public function wh()
    {
        $result = [];

        $eqKeys = ['dest_city_pid', 'is_draft', 'day_num'];

        $likeKeys = ['title'];

        $inKeys = ['user_ids' => 'created_user_id'];

        $btKeys = ['bg_day_num' => 'day_num'];

        foreach ($this->whereParams as $key => $value) {
            if (isset($value) && in_array($key, $eqKeys)) {
                $result[] = ['column' => $key, 'operator' => '=', 'value' => $value];
            }
            if (isset($value) && $value && in_array($key, $likeKeys)) {
                $result[] = ['column' => $key, 'operator' => 'like', 'value' => '%' . $value . '%'];
            }
            if (isset($value) && $value && in_array($key, array_keys($inKeys))) {
                $result[] = ['column' => $inKeys[$key], 'operator' => 'in', 'value' => $value];
            }
            if (isset($value) && $value && in_array($key, array_keys($btKeys))) {
                $result[] = ['column' => $btKeys[$key], 'operator' => '>', 'value' => $value];
            }
        }
        return $result;
    }

    /**
     * 构建关系查询（针对Kktline）
     */
    public function relation()
    {
        return ['days'];
    }

    public function orderBy()
    {
        return ['id', 'desc'];
    }

}