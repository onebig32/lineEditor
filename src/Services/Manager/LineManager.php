<?php
namespace Line\Services\Manager;

use Line\Business\DayBusiness;
use Line\Business\ItemBusiness;
use Line\Business\LineBusiness;
use Line\Repository\Entity\DayEntity;
use Line\Repository\Entity\ImgEntity;
use Line\Repository\Entity\ItemEntity;
use Line\Repository\Entity\LineEntity;
use Line\Repository\Orm\KktLine;
use Line\Repository\Orm\KktLineImg;
use Line\Repository\Orm\KktLineItem;
use Line\Repository\Orm\KktLineDay;
use Line\Services\Manager\Dto\CreateEntityDto;
use Line\Services\Manager\Dto\RequestDto;
use DB;
use PhpOffice\PhpWord\Exception\Exception;


/**
 * 线路管理服务
 */
class LineManager
{
    /**
     * 创建线路
     */
    public function createLine(RequestDto $requestDto)
    {
        $createDto = $requestDto->getCreateDto();
        return $this->createEntity(new CreateEntityDto($createDto));
    }

    /**
     * 编辑线路
     */
    public function editLine(RequestDto $requestDto)
    {
        $createDto = $requestDto->getCreateDto();
        return $this->createEntity(new CreateEntityDto($createDto));
    }

    /**
     * 复制线路
     */
    public function copyLine(RequestDto $requestDto)
    {
        $lineEntity = (new KktLine())->getExistEntity($requestDto->getId());
        $lineEntity = (new LineBusiness())->copyLine($lineEntity);
        $createDto = [
            'line' => array_only($lineEntity->toArray(),
                ['title', 'day_num', 'dest_city_pid', 'dest_city_name','cover_url', 'cover_group','cover_img_id','is_draft','is_draft']),
        ];
        $dayIdArray = array_pluck($lineEntity->days,'day_id');
        $dayOrm = new KktLineDay();
        $itemOrm = new KktLineItem();
        $imgOrm = new KktLineImg();
        foreach ($dayIdArray as $dayId) {
            //天数实体
            $dayEntity = $dayOrm->getExistEntity($dayId);
            $dayArray = array_only($dayEntity->toArray(),['day','is_delete']);
            foreach ($dayEntity->items as $item) {
                //单项资源实体
                $itemEntity = $itemOrm->getExistEntity($item['item_id']);
                $itemArray = array_only($itemEntity->toArray(),
                    ['type_id','title','desc','time_type','time','self_care','dest_city_id','dest_city_name','distance','distance_type','is_import','is_delete']
                );
                $itemArray['is_import'] = $item['is_import'];
                //图片实体
                $itemArray['imgs'] = [];
                foreach($itemEntity->imgs as $img){
                    $imgEntity = $imgOrm->getExistEntity($img['img_id']);
                    $imgArray = array_only($imgEntity->toArray(),
                        ['large_url','middle_url','small_url','group_large','group_middle','group_small','is_delete']
                    );
                    $imgArray['cover'] = $lineEntity->cover_img_id == $img['img_id']? 1:0;
                    $itemArray['imgs'][] = $imgArray;
                }
                $dayArray['items'][]= $itemArray;
            }
            $createDto['tours'][] = $dayArray;
        }
        return $this->createEntity(new CreateEntityDto($createDto));
    }

    /**
     * 删除线路
     */
    public function deleteLine(RequestDto $requestDto)
    {
        $lineOrm = new KktLine();
        $lineEntity = $lineOrm->getExistEntity($requestDto->getId());
        $lineEntity = (new LineBusiness())->deleteLine($lineEntity);
        $lineOrm->saveEntity($lineEntity);

        return true;
    }

    /**
     * 更新实体集合
     */
    protected function createEntity(CreateEntityDto $requestDto)
    {
        // 构建图片实体集合
        $imgEntitys = [];
        foreach ($requestDto->getImgData() as $key => $row) {
            foreach ($row as $v) {
                $tmpEntity = new ImgEntity();
                $tmpEntity->arrayToProp($v);
                $imgEntitys[$key][] = $tmpEntity;
            }
        }
        // 构建资源实体集合
        $itemEntitys = [];
        $itemBusiness = new ItemBusiness();
        foreach ($requestDto->getItemData() as $key => $row) {
            foreach ($row as $v) {
                $tmpEntity = new ItemEntity();
                $tmpEntity->arrayToProp($v);
                $tmpEntity = $itemBusiness->createItem($tmpEntity);
                $itemEntitys[$key][] = $tmpEntity;
            }
        }
        // 构建线路实体
        $lineEntity = (new LineEntity())->arrayToProp($requestDto->getLineData());
        $lineEntity = (new LineBusiness())->createLine($lineEntity);
        // 构建线路天数实体
        $dayBusiness = new DayBusiness();
        $dayEntitys = [];
        foreach ($requestDto->getDayData() as $key => $row) {
            $tmpEntity = new DayEntity();
            $tmpEntity->arrayToProp($row);
            $tmpEntity = $dayBusiness->createDay($tmpEntity);
            $dayEntitys[$key] = $tmpEntity;
        }
        $lineOrm = new KktLine();
        $dayOrm = new KktLineDay();
        $imgOrm = new KktLineImg();
        $itemOrm = new KktLineItem();
        try {
            DB::beginTransaction();
            //保存图片实体
            $imgIds = [];
            foreach ($imgEntitys as $key => $entity) {
                foreach ($entity as $img) {
                    $imgId = $imgOrm->saveEntity($img);
                    if ($img->is_delete == 1) {
                        $imgIds[$key][] = $imgId;
                    }
                    //线路封面
                    if ($img->cover == 1) {
                        $lineEntity->cover_img_id = $imgId;
                    }
                }
            }
            //保存资源单项实体
            $itemIds = [];
            $importArray = [];
            $index = 0;
            foreach ($itemEntitys as $itemKey => $entity) {
                foreach ($entity as $item) {
                    $item->img_ids = $imgIds[$index];
                    $itemId = $itemOrm->saveEntity($item);
                    if ($item->is_delete == 1) {
                        $itemIds[$itemKey][] = $itemId;
                        $importArray[$itemKey][] = $item->is_import;
                    }
                    $index++;
                }
            }
            //保存线路天数实体
            $dayIds = [];
            foreach ($dayEntitys as $key => $entity) {
                $entity->import_arrays = $importArray[$key];
                $entity->item_ids = $itemIds[$key];
                $dayId = $dayOrm->saveEntity($entity);
                if ($entity->is_delete == 1) {
                    $dayIds[] = $dayId;
                }
            }
            //保存线路实体
            $lineEntity->day_ids = $dayIds;
            $lineId = $lineOrm->saveEntity($lineEntity);
            DB::commit();
            return $lineId;
        } catch (\Exception $e) {
            DB::rollback();
            throw new Exception($e);
        }
    }
}