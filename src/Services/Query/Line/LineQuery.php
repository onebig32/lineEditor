<?php
namespace Line\Services\Query\Line;

use Line\Repository\Orm\KktLine;
use Line\Services\Query\Day\DayQuery;
use Line\Services\Query\Img\ImgQuery;
use Line\Services\Query\Item\ItemQuery;

class LineQuery
{
    private $lineOrm;

    public function __construct()
    {
        $this->lineOrm = new KktLine();
    }

    /**
     * 多条件，列表数据查询
     */
    public function listQuery($where, $page, $limit)
    {
        $data = $result = $this->lineOrm->tableQuery(new QueryWhereDto($where), $page, $limit);
        $result['data'] = [];
        $dayQueryService = new DayQuery();
        $itemQueryService = new ItemQuery();
        foreach ($data['data'] as $k => $orm) {
            //封面图片地址
            $orm->cover_url_ = $orm->getCoverUrl();
            //获取天数数据
            $dayIdArray = $orm->days->pluck('day_id');
            $dayResDto = $dayQueryService->listQuery(['ids' => $dayIdArray], 1, count($dayIdArray));
            $days = [];
            foreach ($dayResDto['data'] as $dayDto) {
                $dayArr = $dayDto->baseData(['id', 'day']);
                $itemIdArray = [];
                foreach ($dayDto->getItems() as $key => $item) {
                    if (!$item['is_import']) {
                        continue;
                    }
                    $itemIdArray[] = $item['item_id'];
                }
                //获取天数单项资源数据
                if ($itemIdArray) {
                    $itemResDto = $itemQueryService->listQuery(['ids' => $itemIdArray], 1, count($itemIdArray));
                    $itemTitleArray = [];
                    foreach ($itemResDto['data'] as $row) {
                        $itemTitleArray[] = $row->getTitle();
                    }
                    $dayArr['title'] = implode(',', $itemTitleArray);
                }
                $days[] = $dayArr;
            }
            $orm->tours = $days;
            $result['data'][$k] = (new QueryResultDto($orm));

        }
        return $result;
    }

    /**
     * 查询明细
     * @param int $id 线路id
     */
    public function getDetailForId($id = 0)
    {
        //线路数据
        $lineOrm = $this->lineOrm->getOrmForId($id);
        $lineOrm->cover_url_ = $lineOrm->getCoverUrl();
        $result = array_only($lineOrm->toArray(), ['id', 'title', 'cover_img_id','cover_url_']);
        $dayQueryService = new DayQuery();
        $itemQueryService = new ItemQuery();
        $imgQueryService = new ImgQuery();
        //天数数据
        $dayIdArray = $lineOrm->days->pluck('day_id');
        $dayResDto = $dayQueryService->listQuery(['ids' => $dayIdArray], 1, count($dayIdArray));
        $days = [];
        $itemKeys = ['id', 'type_id', 'title', 'desc', 'time_type', 'time',
            'self_care', 'dest_city_id', 'dest_city_name', 'distance', 'distance_type'];
        $imgKeys = ['id', 'large_url', 'middle_url', 'small_url', 'group_large', 'group_middle', 'group_small'];
        if ($dayIdArray) {
            foreach ($dayResDto['data'] as $dayDto) {
                $dayArr = $dayDto->baseData(['id', 'day']);
                $dayArr['items'] = [];
                $itemIdArray = $dayDto->getItems()->pluck('item_id');
                //单项资源数据
                if ($itemIdArray) {
                    $itemResDto = $itemQueryService->listQuery(['ids' => $itemIdArray, 'withImgs' => true], 1, count($itemIdArray));
                    foreach ($itemResDto['data'] as $itemDto) {
                        $itemArray = $itemDto->baseData($itemKeys);
                        //图片数据
                        $imgIdArray = $itemDto->getImgs()->pluck('img_id');
                        if ($imgIdArray) {
                            $imgResDto = $imgQueryService->listQuery(['ids' => $imgIdArray], 1, count($imgIdArray));
                            foreach ($imgResDto['data'] as $imgDto) {
                                $imgData = $imgDto->baseData($imgKeys);;
                                $imgData['large_url_'] = app('Fastdfs')->localToUrl($imgData['group_large'], $imgData['large_url']);
                                $imgData['middle_url_'] = app('Fastdfs')->localToUrl($imgData['group_middle'], $imgData['middle_url']);
                                $imgData['small_url_'] = app('Fastdfs')->localToUrl($imgData['group_small'], $imgData['small_url']);
                                $itemArray['imgs'][] = $imgData;
                            }
                        }
                        $dayArr['items'] = $itemArray;
                    }
                }
                $days[] = $dayArr;
            }
        }
        $result['tours'] = $days;
        return $result;
    }


    /**
     * 根据id查询
     */
    public function getOneForId($id)
    {
        return new QueryResultDto($this->lineOrm->getOrmForId($id));
    }


}