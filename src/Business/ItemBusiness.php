<?php
namespace Line\Business;

use Line\Business\Exceptions\LineException;
use Line\Repository\Entity\ItemEntity;

class ItemBusiness
{
    /**
     * 创建单项资源
     */
    public function createItem(ItemEntity $itemEntity)
    {
        if (!$itemEntity) {
            throw new LineException('单项资源不存在', '003');
        }
        return $itemEntity;
    }


}