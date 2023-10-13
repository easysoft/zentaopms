<?php
declare(strict_types=1);
/**
 * The all view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     execution
 * @link        http://www.zentao.net
 */

namespace zin;

jsVar('status',    $status);
jsVar('projectID', $projectID);
jsVar('orderBy',   $orderBy);
jsVar('productID', $productID);
jsVar('typeList', $lang->execution->typeList);
jsVar('delayed', $lang->execution->delayed);

$footToolbar = array();
$canBatchEdit         = common::hasPriv('execution', 'batchEdit');
$canBatchChangeStatus = common::hasPriv('execution', 'batchChangeStatus');
$canBatchAction       = $canBatchEdit || $canBatchChangeStatus;
if($canBatchAction)
{
    $editClass = $canBatchEdit ? 'batch-btn' : 'disabled';
    $footToolbar['items'][] = array(
        'type'  => 'btn-group',
        'items' => array(
            array('text' => $lang->edit, 'className' => "secondary size-sm {$editClass}", 'btnType' => 'primary', 'data-url' => createLink('execution', 'batchEdit')),
        )
    );

    if($canBatchChangeStatus)
    {
        $statusList = array();
        foreach($lang->execution->statusList as $key => $value)
        {
            $statusList[] = array('text' => $value, 'class' => 'batch-btn ajax-btn', 'data-url' => createLink('execution', 'batchChangeStatus', "status=$key"));
        }

        menu
        (
            set::id('navStatus'),
            set::className('dropdown-menu'),
            set::items($statusList)
        );

        $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->statusAB,   'className' => 'btn btn-caret size-sm secondary', 'url' => '#navStatus', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start');
    }
}

/* Waterfall project use another actions. */
$config->projectExecution->dtable->fieldList['actions']['menu'] = $config->projectExecution->dtable->fieldList['actions'][$project->model];

$executions = $this->execution->generateRow($executionStats, $users, $avatarList);
$executions = initTableData($executions, $config->projectExecution->dtable->fieldList, $this->project);

/* Generate data table fields. */
$fnGenerateCols = function() use ($config, $project)
{
    $fieldList = $config->projectExecution->dtable->fieldList;

    /* waterfall & waterfallplus model with different edit link. */
    if(in_array($project->model, array('waterfall', 'waterfallplus')))
    {
        $fieldList['actions']['actionsMap']['edit']['url'] = createLink('programplan', 'edit', "stageID={rawID}&projectID={projectID}");
    }

    return array_values($fieldList);
};

/* zin: Define the feature bar on main menu. */
$checked = $this->cookie->showTask ? 'checked' : '';
$productMenuLink = createLink(
    $this->app->rawModule,
    $this->app->rawMethod,
    array(
        'status'     => $status,
        'projectID'  => $projectID,
        'orderBy'    => $orderBy,
        'productID'  => '{key}'
    )
);

$productItems = array();
foreach($productList as $key => $value) $productItems[] = array('text' => $value, 'id' => $key);

featureBar
(
    to::before(productMenu(set
    ([
        'items' => $productItems,
        'activeKey' => $productID,
        'closeLink' => '#',
        'link' => $productMenuLink
    ]))),
    set::current($status),
    set::linkParams("status={key}&projectID={$projectID}&orderBy={$orderBy}&productID={$productID}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"),
    li(searchToggle()),
    li
    (
        checkbox
        (
            set::id('showTask'),
            set::name('showTask'),
            set::checked($checked),
            set::text($lang->programplan->stageCustom->task),
            set::rootClass('ml-4'),
            on::change('showTask')
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
        'url'         => createLink('execution', 'export', "status={$status}&productID={$productID}&orderBy={$orderBy}&from=project"),
    ))) : null,
    hasPriv('execution', 'create') ? item(set(array
    (
        'icon' => 'plus',
        'text' => $lang->execution->createExec,
        'class' => "primary create-execution-btn",
        'url'   => $createLink,
    ))) : null
);

dtable
(
    set::userMap($users),
    set::cols($fnGenerateCols()),
    set::data($executions),
    set::checkable($canBatchAction),
    set::fixedLeftWidth('44%'),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::footToolbar($footToolbar),
    set::footPager(
        usePager
        (
            array('linkCreator' => helper::createLink('project', 'execution', "status={$status}&projectID=$projectID&orderBy={$orderBy}&productID={$productID}&recTotal={recTotal}&recPerPage={recPerPage}&page={page}"))
        ),
    ),
);

render();
