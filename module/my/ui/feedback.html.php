<?php
declare(strict_types=1);
/**
 * The feedback view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

featureBar
(
    set::current($type),
    set::linkParams("mode={$mode}&type={key}&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    li(searchToggle(set::module($this->app->rawMethod . 'Feedback'), set::open($type == 'bysearch')))
);

foreach($feedbacks as $feedback)
{
    $feedback->solution = zget($lang->feedback->solutionList, $feedback->solution, '');
}

$cols = $this->loadModel('datatable')->getSetting($this->moduleName);
$cols['actions']['list']['edit']['data-toggle'] = 'modal';
$feedbacks = initTableData($feedbacks, $cols, $this->feedback);

if(!empty($cols['product'])) $cols['product']['map'] = $allProducts;
if(!empty($cols['module']))  $cols['module']['map']  = $modules;
if(!empty($cols['dept']))    $cols['dept']['map']    = $depts;

$canBatchEdit     = common::hasPriv('feedback', 'batchEdit');
$canBatchClose    = common::hasPriv('feedback', 'batchClose');
$canBatchAssignTo = common::hasPriv('feedback', 'batchAssignTo');
$canBatchAction   = $canBatchEdit || $canBatchClose || $canBatchAssignTo;

$footToolbar     = array();
$assignedToItems = array();
foreach($users as $key => $value)
{
    if($value) $assignedToItems[] = array('text' => $value, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('feedback', 'batchAssignTo', "assignedTo=$key"));
}

if($canBatchAction)
{
    $footToolbar['items'] = array();
    if($canBatchEdit)
    {
        $footToolbar['items'][] = array('text' => $lang->edit, 'className' => 'primary batch-btn not-open-url', 'data-url' => createLink('feedback', 'batchEdit', "browseType=$type"));
    }
    if($canBatchClose)
    {
        $footToolbar['items'][] = array('text' => $lang->close, 'className' => 'primary batch-btn not-open-url', 'data-url' => createLink('feedback', 'batchClose'));
    }
    if($canBatchAssignTo)
    {
        $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->feedback->assignedTo, 'type' => 'dropdown', 'data-placement' => 'top-start', 'items' => $assignedToItems, 'data-menu' => array('searchBox' => true));
    }
    $footToolbar['btnProps'] = array('size' => 'sm', 'btnType' => 'secondary');
}

dtable
(
    set::cols(array_values($cols)),
    set::data(array_values($feedbacks)),
    set::checkable($canBatchAction),
    set::userMap($users),
    set::fixedLeftWidth('44%'),
    set::orderBy($orderBy),
    set::customCols(true),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$type}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager()),
    set::footToolbar($footToolbar)
);

render();
