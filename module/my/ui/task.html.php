<?php
declare(strict_types=1);
/**
 * The task view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

jsVar('todayLabel', $lang->today);
jsVar('yesterdayLabel', $lang->yesterday);
jsVar('childrenAB', $lang->task->childrenAB);
jsVar('parentAB', $lang->task->parentAB);
jsVar('multipleAB', $lang->task->multipleAB);
jsVar('delayWarning', $lang->task->delayWarning);

featureBar
(
    set::current($type),
    set::linkParams("mode={$mode}&type={key}&param=&orderBy={$orderBy}"),
    li(searchToggle(set::module($this->app->rawMethod . 'Task'), set::open($type == 'bySearch')))
);

$viewType = $this->cookie->taskViewType ? $this->cookie->taskViewType : 'tree';
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
            'hint'      => $lang->task->viewTypeList['tiled']
        ), array
        (
            'icon'      => 'treeview',
            'class'     => 'switchButton btn-icon' . ($viewType == 'tree' ? ' text-primary' : ''),
            'data-type' => 'tree',
            'hint'      => $lang->task->viewTypeList['tree']
        ))
    )))
);
if($viewType == 'tiled')
{
    $config->my->task->dtable->fieldList['name']['nestedToggle'] = false;
    $tasks = $this->task->mergeChildIntoParent($tasks);
}

$canBatchEdit  = common::hasPriv('task', 'batchEdit');
$canBatchClose = common::hasPriv('task', 'batchClose') && $type != 'closedBy';
$footToolbar = array('items' => array
(
    $canBatchEdit  ? array('text' => $lang->edit,  'className' => 'batch-btn',          'data-url' => helper::createLink('task', 'batchEdit', "executionID=0&from={$app->rawMethod}"))  : array(),
    $canBatchClose ? array('text' => $lang->close, 'className' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('task', 'batchClose')) : array()
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

if($type == 'assignedTo') unset($config->my->task->dtable->fieldList['assignedTo']);
if($type == 'openedBy')   unset($config->my->task->dtable->fieldList['openedBy']);
if($type == 'finishedBy') unset($config->my->task->dtable->fieldList['finishedBy']);

$tasks = initTableData($tasks, $config->my->task->dtable->fieldList, $this->task);
$cols  = array_values($config->my->task->dtable->fieldList);
$lang->task->statusList['changed'] = $lang->task->storyChange;
foreach($tasks as $task)
{
    if(!isset($task->rawStatus)) $task->rawStatus = $task->status;
    $task->status = $this->processStatus('task', $task);
    if($app->rawMethod == 'contribute') $task = $this->task->processConfirmStoryChange($task);
}
$data  = array_values($tasks);

if($config->edition == 'ipd')
{
    static $canStartExecution = '';
    static $executionID       = '';
    foreach($data as $task)
    {
        if(empty($canStartExecution) || $executionID != $task->execution)
        {
            $executionID       = $task->execution;
            $canStartExecution = $this->execution->checkStageStatus($executionID, 'start');
        }

        if(!empty($canStartExecution['disabled']))
        {
            foreach($task->actions as $key => $action)
            {
                if(in_array($action['name'], array('start', 'finish', 'record')))
                {
                    $tip = $action['name'] == 'record' ? 'recordWorkhourTip' : $action['name'] . 'Tip';
                    $task->actions[$key]['disabled'] = true;
                    $task->actions[$key]['hint']     = $lang->task->disabledTip->$tip;
                }
            }
        }

        $task->estimate = helper::formatHours($task->estimate);
        $task->consumed = helper::formatHours($task->consumed);
        $task->left     = helper::formatHours($task->left);
    }
}

dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('44%'),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::checkable(true),
    set::defaultSummary(array('html' => $summary)),
    set::checkedSummary($lang->execution->checkedSummary),
    set::checkInfo(jsRaw('function(checkedIDList){return window.setStatistics(this, checkedIDList);}')),
    set::canRowCheckable(jsRaw('function(rowID){return this.getRowInfo(rowID).data.canBeChanged;}')),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$type}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::emptyTip($lang->task->noTask)
);

render();
