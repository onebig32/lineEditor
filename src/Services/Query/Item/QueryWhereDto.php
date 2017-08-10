<?php
namespace Line\Services\Query\Item;

class QueryWhereDto
{
    private $whereParams;

    public function __construct(array $where)
    {
        $this->whereParams = $where;
    }

    /**
     * 构建基础查询（针对KktlineItem）
     */
    public function wh()
    {
        $result = [];
        $eqKeys = ['id', 'title', 'type_id'];
        $inKeys = ['ids' => 'id'];
        foreach ($this->whereParams as $key => $value) {
            if ($value && in_array($key, $eqKeys)) {
                $result[] = ['column' => $key, 'operator' => '=', 'value' => $value];
            }
            if ($value && in_array($key, array_keys($inKeys))) {
                $result[] = ['column' => $inKeys[$key], 'operator' => 'in', 'value' => $value];
            }
        }
        return $result;
    }

    /**
     * 构建关系查询（针对 KktlineItem 与 KktLineItemImgs 一对多的关系）
     */
    public function relation()
    {
        $relation = [];
        if (array_has($this->whereParams, ['withImgs'])) {
            $relation = ['imgs'];
        }
        return $relation;
    }

    /**
     * 定义排序
     */
    public function orderBy()
    {
        return ['id', 'asc'];
    }
}