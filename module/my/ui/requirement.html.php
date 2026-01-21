<?php
declare(strict_types=1);
/**
 * The requirement view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';
jsVar('window.globalSearchType', 'requirement');
jsVar('showGrade',  $showGrade);
jsVar('gradeGroup', $gradeGroup);

jsVar('recall',       $lang->story->recall);
jsVar('recallChange', $lang->story->recallChange);

featureBar
(
    set::current($type),
    set::linkParams("mode=requirement&type={key}&param="),
    li(searchToggle(set::module($this->app->rawMethod . 'Requirement'), set::open($type == 'bysearch')))
);

$viewType = $this->cookie->storyViewType ? $this->cookie->storyViewType : 'tree';
toolbar
(
    item(set(array
    (
        'type'  => 'btnGroup',
        'items' => array(array
        (
            'icon'      => 'list',
            'class'     => 'btn-icon switchButton' . ($viewType == 'tiled' ? ' text-primary' : ''),
            'data-type' => 'tiled',
            'hint'      => $lang->story->viewTypeList['tiled']
        ), array
        (
            'icon'      => 'treeview',
            'class'     => 'switchButton btn-icon' . ($viewType == 'tree' ? ' text-primary' : ''),
            'data-type' => 'tree',
            'hint'      => $lang->story->viewTypeList['tree']
        ))
    )))
);

$canBatchEdit     = common::hasPriv('requirement', 'batchEdit');
$canBatchReview   = common::hasPriv('requirement', 'batchReview');
$canBatchAssignTo = common::hasPriv('requirement', 'batchAssignTo');
$canBatchClose    = common::hasPriv('requirement', 'batchClose');
$canBatchAction   = $canBatchEdit || $canBatchReview || $canBatchAssignTo || $canBatchClose;

$reviewItems = array();
if($canBatchReview)
{
    $rejectItems = array();
    foreach($lang->story->reasonList as $key => $reason)
    {
        if(!$key || $key == 'subdivided' || $key == 'duplicate') continue;
        $rejectItems[] = array('text' => $reason, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('requirement', 'batchReview', "result=reject&reason={$key}&storyType=requirement"));
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
            $reviewItems[] = array('text' => $result, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('requirement', 'batchReview', "result={$key}&reason=&storyType=requirement"));
        }
    }
}

$assignedToItems = array();
if($canBatchAssignTo)
{
    $pinyinItems = common::convert2Pinyin($users);
    foreach($users as $key => $value)
    {
        if(empty($key) || $key == 'closed') continue;
        $assignedToItems[] = array('text' => $value, 'keys' => zget($pinyinItems, $value, ''), 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('requirement', 'batchAssignTo', "storyType=requirement&assignedTo={$key}"));
    }
}

$footToolbar = array('items' => array
(
    $canBatchEdit ?     array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => helper::createLink('requirement', 'batchEdit', "productID=0&executionID=0&branch=0&storyType=requirement&from={$app->rawMethod}")) : null,
    $canBatchReview ?   array('caret' => 'up', 'text' => $lang->story->review, 'type' => 'dropdown', 'items' => $reviewItems, 'data-placement' => 'top-start') : null,
    $canBatchAssignTo ? array('caret' => 'up', 'text' => $lang->story->assignedTo, 'type' => 'dropdown', 'items' => $assignedToItems, 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)) : null,
    $canBatchClose ?    array('text' => $lang->story->close, 'className' => 'batch-btn', 'data-url' => helper::createLink('requirement', 'batchClose', "productID=0&executionID=0&storyType=requirement&from={$app->rawMethod}")) : null
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

if($canBatchAction) $config->my->requirement->dtable->fieldList['id']['type'] = 'checkID';

$stories = initTableData($stories, $config->my->requirement->dtable->fieldList, $this->story);
foreach($stories as $id => $story)
{
    if(!isset($story->actions)) continue;
    foreach($story->actions as $key => $action)
    {
        if(!empty($story->frozen) && in_array($action['name'], array('edit', 'change'))) $stories[$id]->actions[$key]['hint'] = sprintf($lang->story->frozenTip, $lang->story->{$action['name']});
    }
}

if($viewType == 'tiled') $config->my->requirement->dtable->fieldList['title']['nestedToggle'] = false;
$cols = array_values($config->my->requirement->dtable->fieldList);
$data = array_values($stories);
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
    set::emptyTip(sprintf($lang->my->noData, $lang->URCommon))
);

render();
