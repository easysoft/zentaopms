<?php
declare(strict_types=1);
/**
 * The all view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('status',    $status);
jsVar('projectID', $projectID);
jsVar('orderBy',   $orderBy);
jsVar('productID', $productID);
jsVar('typeList', $lang->execution->typeList);
jsVar('delayed', $lang->execution->delayed);
jsVar('delayWarning', $lang->task->delayWarning);
jsVar('pageExecSummary', $lang->execution->pageExecSummary);
jsVar('checkedExecSummary', $lang->execution->checkedExecSummary);
jsVar('confirmCreateStage', $lang->project->confirmCreateStage);

$searchTask = strtolower($status) == 'bysearch';

$footToolbar = array();
$canModify            = common::canModify('project', $project);
$canBatchEdit         = hasPriv('execution', 'batchEdit');
$canBatchChangeStatus = hasPriv('execution', 'batchChangeStatus');
$canBatchAction       = $canModify && ($canBatchEdit || $canBatchChangeStatus);
if($canBatchAction)
{
    if($canBatchEdit && empty($hasFrozenExecutions))
    {
        $footToolbar['items'][] = array
        (
            'type'  => 'btn-group',
            'items' => array
            (
                array('text' => $lang->edit, 'className' => "secondary size-sm batch-btn", 'btnType' => 'primary', 'data-url' => createLink('execution', 'batchEdit')),
            )
        );
    }

    if($canBatchChangeStatus)
    {
        $statusItems = array();
        foreach($lang->execution->statusList as $key => $value)
        {
            $statusItems[] = array('text' => $value, 'innerClass' => 'batch-btn ajax-btn', 'data-url' => createLink('execution', 'batchChangeStatus', "status=$key"));
        }

        $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->statusAB,   'className' => 'btn btn-caret size-sm secondary', 'items' => $statusItems, 'type' => 'dropdown', 'data-placement' => 'top-start');
    }
}

/* Generate data table fields. */
$fieldList = $config->project->execution->dtable->fieldList;
$fieldList['status']['statusMap']['changed'] = $lang->task->storyChange;

if(!empty($project->isTpl)) unset($fieldList['deliverable']);

/* waterfall & waterfallplus & ipd model with different edit link. */
if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')))
{
    $fieldList['actions']['actionsMap']['edit']['data-size'] = 'md';
    $fieldList['actions']['actionsMap']['edit']['url'] = createLink('programplan', 'edit', "stageID={rawID}&projectID={projectID}");

    $fieldList['actions']['actionsMap']['createChildStage']['url'] = 'javascript:confirmCreateStage({projectID}, {productID}, {rawID}, {hasChild});';
}
if(!$this->cookie->showStage && !$this->cookie->showTask)
{
    $fieldList['name']['type'] = 'title';
    if(!in_array($project->model, array('waterfall', 'waterfallplus', 'ipd'))) unset($fieldList['name']['nestedToggle']);
}
if(!$project->hasProduct) unset($fieldList['productName']);

$config->project->execution->dtable->fieldList = $fieldList;
$fieldList = $this->loadModel('datatable')->getSetting('project', 'execution');
$fieldList['name']['name'] = 'nameCol';
$fieldList['actions']['width'] = '160';

foreach(array_keys($fieldList['actions']['actionsMap']) as $actionKey) unset($fieldList['actions']['actionsMap'][$actionKey]['text']);
$fieldList['status']['statusMap']['changed'] = $lang->task->storyChange;

$executions = $this->execution->generateRow($executionStats, $users, $avatarList);
foreach($executions as $execution) $execution->nameCol = $execution->name;

/* zin: Define the feature bar on main menu. */
$productItems = array();
foreach($productList as $key => $value) $productItems[] = array('text' => $value, 'active' => $key == $productID, 'url' => createLink('project', 'execution', "status={$status}&projectID={$projectID}&orderBy={$orderBy}&productID={$key}"));

$productName  = !empty($product) ? $product->name : '';
$showProduct  = (in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')) && $project->stageBy == 'product') || in_array($project->model, array('agileplus', 'scrum'));
$productLabel = $productName ? $productName : $lang->product->all;
featureBar
(
    ($showProduct && $project->hasProduct && empty($project->isTpl)) ? to::leading
    (
        dropdown
        (
            to('trigger', btn($productLabel, setClass('ghost'))),
            set::items($productItems)
        )
    ) : null,
    set::module('project'),
    set::method('execution'),
    set::current($status),
    set::link('project', 'execution', "status={key}&projectID={$projectID}&orderBy={$orderBy}&productID={$productID}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"),
    li
    (
        checkbox
        (
            set::id('showTask'),
            set::name('showTask'),
            set::checked($this->cookie->showTask ? 'checked' : ''),
            set::text($lang->programplan->stageCustom['task']),
            set::rootClass('ml-4')
        )
    ),
    $project->model == 'ipd' ? li
    (
        checkbox
        (
            set::id('showStage'),
            set::name('showStage'),
            set::checked($this->cookie->showStage ? 'checked' : ''),
            set::text($lang->programplan->stageCustom['point']),
            set::rootClass('ml-4')
        )
    ) : null,
    $this->cookie->showTask ? li(setClass('ml-2'), searchToggle(set::module('projectTask'), set::open($searchTask))) : null
);

/* zin: Define the toolbar on main menu. */
$createLink       = $isStage ? createLink('programplan', 'create', "projectID={$projectID}&productID={$productID}") : createLink('execution', 'create', "projectID={$projectID}");
$canModifyProject = common::canModify('project', $project);
if(!$canModifyProject) $fieldList['actions']['actionsMap'] = array();
toolbar
(
    in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')) && in_array($this->config->edition, array('max', 'ipd')) ? btnGroup
    (
        a(setClass('btn square'), icon('gantt-alt'), set::title($lang->programplan->gantt), set::href(createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=gantt"))),
        a(setClass('btn square text-primary'), icon('list'), set::title($lang->project->bylist))
    ) : null,
    hasPriv('execution', 'export') ? item(set(array
    (
        'icon'        => 'export',
        'text'        => $lang->programplan->exporting,
        'class'       => "ghost export",
        'data-toggle' => "modal",
        'url'         => createLink('execution', 'export', "status={$status}&productID={$productID}&orderBy={$orderBy}&from=project")
    ))) : null,
    $canModifyProject && common::hasPriv('programplan', 'create') && $isStage && empty($product->deleted) ? item(set(array
    (
        'icon'  => 'plus',
        'text'  => $lang->programplan->create,
        'class' => "primary create-execution-btn",
        'url'   => $createLink
    ))) : null,
    $canModifyProject && hasPriv('execution', 'create') && !$isStage && $project->model != 'agileplus' ? item(set(array
    (
        'icon'  => 'plus',
        'text'  => $isStage ? $lang->programplan->create : $lang->execution->create,
        'class' => "primary create-execution-btn",
        'url'   => $createLink
    ))) : null,
    $canModifyProject && hasPriv('execution', 'create') && !$isStage && $project->model == 'agileplus' ?  btngroup(
        setClass('create-execution-btn'),
        btn(setClass('btn primary'), set::icon('plus'), set::url($createLink), $lang->execution->create),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items
            (
                array('text' => $lang->execution->create, 'url' => $createLink),
                array('text' => $lang->project->createKanban, 'url' => createLink('execution', 'create', "projectID={$projectID}&executionID=0&copyExecutionID=&planID=0&confirm=no&productID=0&extra=type=kanban"))
            ),
            set::placement('bottom-end')
        )
    ) : null
);

$canCreateExecution = $canModifyProject &&  $isStage ? common::hasPriv('programplan', 'create') : common::hasPriv('execution', 'create');
dtable
(
    set::userMap($users),
    set::cols($fieldList),
    set::data($executions),
    set::customCols(true),
    set::checkable($canBatchAction),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::canRowCheckable(jsRaw("function(rowID){return this.getRowInfo(rowID).data.id.indexOf('pid') > -1;}")),
    set::checkInfo(jsRaw("function(checkedIDList){ return window.footerSummary(this, checkedIDList);}")),
    set::footToolbar($footToolbar),
    set::orderBy($orderBy),
    set::sortLink(createLink('project', 'execution', "status={$status}&projectID=$projectID&orderBy={name}_{sortType}&productID={$productID}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager(array('linkCreator' => createLink('project', 'execution', "status={$status}&projectID=$projectID&orderBy={$orderBy}&productID={$productID}&recTotal={recTotal}&recPerPage={recPerPage}&page={page}")))),
    set::emptyTip(!$searchTask ? $lang->execution->noExecution : $lang->task->noTask),
    set::createTip($isStage ? $lang->programplan->create : $lang->execution->create),
    set::createLink($canCreateExecution && !$searchTask ? $createLink : ''),
    set::createAttr($isStage ? 'data-app="project"' : 'data-app="execution"')
);

render();
