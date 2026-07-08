<?php
declare(strict_types=1);
/**
 * The flow view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gunagming Sun <sungunagming@chandao.com>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

/* 判断dtable按钮高亮，要将rawModule和rawMethod设置为flow的模块名、方法名. */
$app->rawModule = $flow->module;
$app->rawMethod = 'browse';

$dataList = initTableData($dataList, $cols, $this->flow);

/* 追加导航、判断导航高亮，要使用my-work. */
$app->rawModule = 'my';
$app->rawMethod = 'work';

foreach($dataList as $id => $data)
{
    if(empty($data->actions)) continue;
    foreach($data->actions as $actionID => $action)
    {
        if(!is_array($action)) continue;
        if($action['name'] == 'approvalsubmit' && !in_array($data->reviewStatus, array('', 'wait', 'reject', 'reverting'))) $dataList[$id]->actions[$actionID]['disabled'] = true;
        if($action['name'] == 'approvalcancel' && !$this->approval->canCancel($data)) $dataList[$id]->actions[$actionID]['disabled'] = true;
        if($action['name'] == 'approvalreview' && !isset($pendingReviews[$data->id])) $dataList[$id]->actions[$actionID]['disabled'] = true;
    }
}

$workLinkParams = "mode={$flow->module}&type={$type}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";
$sortLink       = createLink('my', 'work', "{$workLinkParams}&orderBy={name}_{sortType}");

$workParams      = "mode={$flow->module}&type=assignedTo&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";
$featureBarItems = array(array(
    'text'   => $this->lang->my->assignedToMe,
    'active' => $type != 'bysearch',
    'url'    => $this->createLink('my', 'work', $workParams),
    'badge' => array(
        'text' => $pager->recTotal,
        'class' => 'size-sm canvas ring-0 rounded-md'
    )
));

if($browseMode == 'bysearch') unset($featureBarItems[0]['badge']);

featureBar
(
    set::current($type),
    set::items($featureBarItems),
    !empty($canSearch) ? li(searchToggle(set::module($flow->module), set::open($browseMode == 'bysearch'))) : null
);

dtable
(
    setID('dataList'),
    set::cols($cols),
    set::data(array_values($dataList)),
    set::customCols(array(
        'url' => createLink('datatable', 'ajaxcustom', "module={$flow->module}&method=browse"),
        'globalUrl' => createLink('datatable', 'ajaxsaveglobal', "module={$flow->module}&method=browse"),
        'resetUrl' => createLink('datatable', 'ajaxreset', "module={$flow->module}&method=browse"),
        'resetGlobalUrl' => createLink('datatable', 'ajaxreset', "module={$flow->module}&method=browse&system=1"),
    )),
    set::moduleName($flow->module),
    set::checkable(!empty($footToolbar)),
    set::orderBy($orderBy),
    set::sortLink($sortLink),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
);