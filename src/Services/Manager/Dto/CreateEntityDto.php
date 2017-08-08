<?php
namespace Line\Services\Manager\Dto;


/**
 * 构建实体 dto对象
 */

class CreateEntityDto
{
    private $createEntityDto;

    public function __construct($createDto = [])
    {
        $this->createEntityDto = $createDto;
    }

    /**
     * 获取单项资源数组
     * @return array $itemArray
     */
    public function getItemData()
    {
        $data = $this->createEntityDto;
        $itemArray = [];
        foreach ($data['tours'] as $tourKey => $row) {
            $itemArray[$tourKey] = [];
            foreach ($row['items'] as $item) {
                $itemArray[$tourKey][] = array_except($item,['imgs']);
            }
        }
        return $itemArray;
    }

    /**
     * 获取图片数组
     * @return array $tourArray
     */
    public function getImgData()
    {
        $data = $this->createEntityDto;
        $imgArray = [];
        $index = 0;
        foreach ($data['tours'] as $key => $row) {
            foreach ($row['items'] as $itemKey => $item) {
                if (isset($item['imgs']) && $item['imgs']) {
                    foreach ($item['imgs'] as $img) {
                        $imgArray[$index][] = $img;
                    }
                }
                $index++;
            }
        }
        return $imgArray;
    }

    /**
     * 获取线路数组
     * @return array $lineArray
     */
    public function getLineData()
    {
        $data = $this->createEntityDto;
        $lineArray = array_except($data['line'],'days');
        return $lineArray;
    }

    /**
     * 获取线路天数数组
     * @return array $dayArray
     */
    public function getDayData()
    {
        $data = $this->createEntityDto;
        $dayArray = [];
        foreach ($data['tours'] as $key => $row) {
            $dayArray[] = array_only($row, ['id', 'day','is_delete']);
        }
        return $dayArray;
    }


}