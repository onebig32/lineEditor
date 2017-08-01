<?php
namespace Line\Services\Manager;

use Line\Business\LineBusiness;
use Line\Repository\Entity\ItemEntity;
use Line\Repository\Entity\LineEntity;
use Line\Repository\Entity\TourEntity;
use Line\Repository\Orm\KktLine;

use Line\Repository\Orm\KktLineItem;
use Line\Repository\Orm\KktLineTour;
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
     * @param 更新实体集合
     */
    protected function createEntity(CreateEntityDto $requestDto){
        // 构建资源实体集合
        $itemEntitys = [];

        foreach($requestDto->getItemData() as $key=>$row){
            foreach($row as $v){
                $tmpEntity = new ItemEntity();
                $tmpEntity->arrayToProp($v);
                $itemEntitys[$key][] = $tmpEntity;
            }
        }
        // 构建行程实体
        $TourEntitys = [];
        foreach($requestDto->getTourData() as $key=>$row){
            $tmpEntity = new TourEntity();
            $tmpEntity->arrayToProp($row);
            $TourEntitys[$key] = $tmpEntity;
        }
        //$toursEntity = (new TourBusiness())->createTour($toursEntity);
        // 构建线路实体
        $lineEntity = (new LineEntity())->arrayToProp($requestDto->getLineData());
        //$lineEntity = (new LineBusiness())->createLine($lineEntity);
        $lineOrm = new KktLine();
        $tourOrm = new KktLineTour();
        $itemOrm = new KktLineItem();
        try{
            DB::beginTransaction();
            //保存资源单项实体
            $itemIds = [];
            foreach($itemEntitys as $key=>$entity){
                foreach($entity as $item){
                    $itemIds[$key][] = $itemOrm->saveEntity($item);
                }
            }
            //保存行程实体
            $tourIds = [];
            foreach($TourEntitys as $key=>$entity){
                $entity->item_ids = $itemIds[$key];
                $tourIds[] = $tourOrm->saveEntity($entity);
            }
            //保存线路实体
            $lineEntity->tour_ids = $tourIds;
            $lineId = $lineOrm->saveEntity($lineEntity);
            DB::commit();
            return $lineId;
        }catch (\Exception $e){
            DB::rollback();
            throw new Exception($e);
        }
    }

    /**
     * 删除线路
     */
    public function deleteLine()
    {

    }

    /**
     * copey
     */
    public function copyLine()
    {

    }

    /**
     * 编辑线路
     */
    public function editLine(RequestDto $dto)
    {
        $entity = (new KktLine())->getEntity($id);        // 创建实体
    }
}