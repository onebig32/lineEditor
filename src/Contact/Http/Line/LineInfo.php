<?php
namespace Line\Contact\Http\Line;

use App\Http\Controllers\Controller;
use Line\Contact\ReplyDto\LineReplyDto;
use Line\Contact\Rules\LineRules;
use Line\Services\Manager\LineManager;
use Line\Services\Manager\Dto\RequestDto;
use Line\Services\Query\Line\LineQuery;

class LineInfo extends Controller
{
    /**
     * 获取批量数据
     * $_GET[fields] （非必填）需要返回的字段列表，可选值为返回示例值中的可以看到的字段，如"title，day_num，cover_url, tours "
     * $_GET[limit] （非必填）每页条数.每页返回最多返回100条,默认值为14.
     * $_GET[page] （非必填）页码.传入值为1代表第一页,传入值为2代表第二页,依此类推.默认返回的数据是从第一页开始.
     * $_GET[dayNum] （非必填）总天数
     * $_GET[destCityId] （非必填）目的国id
     * $_GET[searchKey] （非必填）搜索关键词：线路名称等
     * $_GET[self] （非必填） 是否获取自己的线路 true 是 false 否
     * $_GET[draft] （非必填） 是否草稿线路 true 是 false 否
     */
    public function listAjax(LineRules $request)
    {
        $requestDto = new RequestDto($request);
        $where = $requestDto->getListWh();
        if ($request->input('self', false)) {
            $userInfo = $this->getSessionInfo();
            $where['user_ids'] = [$userInfo['id']];
        }
        $queryResult = (new LineQuery())->listQuery($where, $requestDto->getPage(), $requestDto->getLimit());
        return (new LineReplyDto())->tableList($queryResult, $requestDto->getField());
    }

    /**
     * 获取单条明细数据
     * $_GET[linId] (必填) 线路Id
     */
    public function getOneAjax(LineRules $request)
    {
        $requestDto = new RequestDto($request);
        $queryResult = (new LineQuery())->getDetailForId($requestDto->getId());
        return (new LineReplyDto())->response($queryResult);
    }

    /**
     * 创建线路
     */
    public function addAjax(LineRules $request)
    {
        $lineId = (new LineManager())->createLine(new RequestDto($request));
        return (new LineReplyDto())->response([$lineId]);
    }

    /**
     * 更新线路
     *
     */
    public function updateAjax(LineRules $request)
    {
        $result = (new LineManager())->editLine(new RequestDto($request));
        return (new LineReplyDto())->response($result);
    }

    /**
     * 删除线路
     * $_POST[lineId] (必填)线路Id
     */
    public function deleteAjax(LineRules $request)
    {
        $result = (new LineManager())->deleteLine(new RequestDto($request));
        return (new LineReplyDto())->response($result);
    }

    /**
     * 复制线路
     * $_POST[lineId] (必填)线路Id
     */
    public function copyAjax(LineRules $request)
    {
        $result = (new LineManager())->copyLine(new RequestDto($request));
        return (new LineReplyDto())->response($result);
    }


}