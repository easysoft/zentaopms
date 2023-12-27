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
jsVar('pageExecSummary', $lang->execution->pageExecSummary);
jsVar('checkedExecSummary', $lang->execution->checkedExecSummary);

$footToolbar = array();
$canBatchEdit         = hasPriv('execution', 'batchEdit');
$canBatchChangeStatus = hasPriv('execution', 'batchChangeStatus');
$canBatchAction       = $canBatchEdit || $canBatchChangeStatus;
if($canBatchAction)
{
    $editClass = $canBatchEdit ? 'batch-btn' : 'disabled';
    $footToolbar['items'][] = array
    (
        'type'  => 'btn-group',
        'items' => array
        (
            array('text' => $lang->edit, 'className' => "secondary size-sm {$editClass}", 'btnType' => 'primary', 'data-url' => createLink('execution', 'batchEdit')),
        )
    );

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
$fnGenerateCols = function() use ($config, $project)
{
    $fieldList = $config->projectExecution->dtable->fieldList;

    /* waterfall & waterfallplus model with different edit link. */
    if(in_array($project->model, array('waterfall', 'waterfallplus')))
    {
        $fieldList['actions']['actionsMap']['edit']['data-size'] = 'md';
        $fieldList['actions']['actionsMap']['edit']['url'] = createLink('programplan', 'edit', "stageID={rawID}&projectID={projectID}");
    }

    if(!$this->cookie->showTask)
    {
        $fieldList['name']['type'] = 'title';
        if(!in_array($project->model, array('waterfall', 'waterfallplus'))) unset($fieldList['name']['nestedToggle']);
    }

    return array_values($fieldList);
};

foreach(array_keys($config->projectExecution->dtable->fieldList['actions']['actionsMap']) as $actionKey) unset($config->projectExecution->dtable->fieldList['actions']['actionsMap'][$actionKey]['text']);
$executions = $this->execution->generateRow($executionStats, $users, $avatarList);

/* zin: Define the feature bar on main menu. */
$productItems = array();
foreach($productList as $key => $value) $productItems[] = array('text' => $value, 'active' => $key == $productID, 'url' => createLink($this->app->rawModule, $this->app->rawMethod, "status={$status}&projectID={$projectID}&orderBy={$orderBy}&productID={$key}"));

$productName = !empty($product) ? $product->name : '';
featureBar
(
    to::leading
    (
        dropdown
        (
            to('trigger', btn($productName ? $productName : $lang->product->all, setClass('ghost'))),
            set::items($productItems)
        )
    ),
    set::current($status),
    set::linkParams("status={key}&projectID={$projectID}&orderBy={$orderBy}&productID={$productID}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"),
    li
    (
        checkbox
        (
            set::id('showTask'),
            set::name('showTask'),
            set::checked($this->cookie->showTask ? 'checked' : ''),
            set::text($lang->programplan->stageCustom->task),
            set::rootClass('ml-4')
        )
    )
);

/* zin: Define the toolbar on main menu. */
$createLink = $isStage ? createLink('programplan', 'create', "projectID={$projectID}&productID={$productID}") : createLink('execution', 'create', "projectID={$projectID}");
toolbar
(
    hasPriv('execution', 'export') ? item(set(array
    (
        'icon'        => 'export',
        'text'        => $lang->programplan->exporting,
        'class'       => "ghost export",
        'data-toggle' => "modal",
        'url'         => createLink('execution', 'export', "status={$status}&productID={$productID}&orderBy={$orderBy}&from=project")
    ))) : null,
    common::hasPriv('programplan', 'create') && $isStage && empty($product->deleted) ? item(set(array
    (
        'icon'  => 'plus',
        'text'  => $lang->programplan->create,
        'class' => "primary create-execution-btn",
        'url'   => $createLink
    ))) : null,
    hasPriv('execution', 'create') && !$isStage && $project->model != 'agileplus' ? item(set(array
    (
        'icon'  => 'plus',
        'text'  => $isStage ? $lang->programplan->create : $lang->execution->create,
        'class' => "primary create-execution-btn",
        'url'   => $createLink
    ))) : null,
    hasPriv('execution', 'create') && !$isStage && $project->model == 'agileplus' ?  btngroup(
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

dtable
(
    set::userMap($users),
    set::cols($fnGenerateCols()),
    set::data($executions),
    set::checkable($canBatchAction),
    set::fixedLeftWidth('44%'),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::canRowCheckable(jsRaw("function(rowID){return this.getRowInfo(rowID).data.id.indexOf('pid') > -1;}")),
    set::checkInfo(jsRaw("function(checkedIDList){ return window.footerSummary(this, checkedIDList);}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager(array('linkCreator' => createLink('project', 'execution', "status={$status}&projectID=$projectID&orderBy={$orderBy}&productID={$productID}&recTotal={recTotal}&recPerPage={recPerPage}&page={page}")))),
    set::emptyTip($lang->execution->noExecution),
    set::createTip($isStage ? $lang->programplan->create : $lang->execution->create),
    set::createLink(hasPriv('execution', 'create') ? $createLink : '')
);

render();
