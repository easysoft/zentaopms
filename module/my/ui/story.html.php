<?php
declare(strict_types=1);
/**
 * The story view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

jsVar('children',   $lang->story->children);
jsVar('childrenAB', $lang->story->childrenAB);

featureBar
(
    set::current($type),
    set::linkParams("mode=story&type={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    li(searchToggle(set::module($this->app->rawMethod . 'Story')))
);

$canBatchEdit     = common::hasPriv('story', 'batchEdit');
$canBatchReview   = common::hasPriv('story', 'batchReview');
$canBatchAssignTo = common::hasPriv('story', 'batchAssignTo');
$canBatchClose    = common::hasPriv('story', 'batchClose');
$canBatchAction   = $canBatchEdit || $canBatchReview || $canBatchAssignTo || $canBatchClose;

$reviewItems = array();
if($canBatchReview)
{
    $rejectItems = array();
    foreach($lang->story->reasonList as $key => $reason)
    {
        if(!$key || $key == 'subdivided' || $key == 'duplicate') continue;
        $rejectItems[] = array('text' => $reason, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('story', 'batchReview', "result=reject&reason={$key}&storyType=story"));
    }

    foreach($lang->story->reviewResultList as $key => $result)
    {
        if(!$key || $key == 'revert') continue;
        if($key == 'reject')
        {
            $reviewItems[] = array('text' => $result, 'class' => 'not-hide-menu', 'items' => $rejectItems);
        }
        else
        {
            $reviewItems[] = array('text' => $result, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('story', 'batchReview', "result={$key}&reason=&storyType=story"));
        }
    }
}

$assignedToItems = array();
if($canBatchAssignTo)
{
    foreach($users as $key => $value)
    {
        if(empty($key) || $key == 'closed') continue;
        $assignedToItems[] = array('text' => $value, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('story', 'batchAssignTo', "storyType=story&assignedTo={$key}"));
    }
}

$footToolbar = array('items' => array
(
   $canBatchEdit     ? array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => helper::createLink('story', 'batchEdit', "productID=0&executionID=0&branch=0&storyType=story&from={$app->rawMethod}")) : null,
   $canBatchReview   ? array('caret' => 'up', 'text' => $lang->story->review, 'type' => 'dropdown', 'items' => $reviewItems, 'data-placement' => 'top-start') : null,
   $canBatchAssignTo ? array('caret' => 'up', 'text' => $lang->story->assignedTo, 'type' => 'dropdown', 'items' => $assignedToItems, 'data-placement' => 'top-start') : null,
   $canBatchClose    ? array('text' => $lang->story->close, 'className' => 'batch-btn', 'data-url' => helper::createLink('story', 'batchClose', "productID=0&executionID=0&storyType=story&from={$app->rawMethod}")) : null
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

if($canBatchAction) $config->my->story->dtable->fieldList['id']['type'] = 'checkID';

$stories = initTableData($stories, $config->my->story->dtable->fieldList, $this->story);
$cols    = array_values($config->my->story->dtable->fieldList);
$data    = array_values($stories);
dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('44%'),
    set::checkable($canBatchAction ? true : false),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$type}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::emptyTip(sprintf($lang->my->noData, $lang->SRCommon))
);

render();
