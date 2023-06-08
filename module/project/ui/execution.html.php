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
            array('text' => $lang->edit, 'class' => "btn primary size-sm {$editClass}", 'btnType' => 'primary', 'data-url' => createLink('execution', 'batchEdit')),
            array('caret' => 'up', 'btnType' => 'primary', 'url' => '#navActions', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
        )
    );

    if($canBatchChangeStatus)
    {
        $statusList = array();
        foreach($lang->execution->statusList as $key => $value)
        {
            $statusList[] = array('text' => $value, 'class' => 'batch-btn', 'data-url' => createLink('execution', 'batchChangeStatus', "status=$key&projectID={$project->id}"));
        }

        menu
        (
            set::id('navStatus'),
            set::class('dropdown-menu'),
            set::items($statusList)
        );
    }
}

/* Waterfall project use another actions. */
$config->projectExecution->dtable->fieldList['actions']['menu'] = $config->projectExecution->dtable->fieldList['actions'][$project->model];

$executions = initTableData($executionStats, $config->projectExecution->dtable->fieldList, $this->project);
$executions = $this->execution->generateRow($executions, $users, $avatarList);

/* zin: Define the feature bar on main menu. */
$checked = $this->cookie->showTask ? 'checked' : '';
featureBar
(
    to::before(productMenu(set
    ([
        'title' => $lang->product->allProduct,
        'items' => $productList,
        'activeKey' => $productID,
        'closeLink' => '#'
    ]))),
    set::current($status),
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
$createLink = $isStage ? createLink('programplan', 'create', "projectID=$projectID&productID=$productID") : createLink('execution', 'create');
toolbar
(
    hasPriv('execution', 'export') ? item(set(array
    (
        'icon'  => 'export',
        'text'  => $lang->programplan->exporting,
        'class' => "ghost export",
        'url'   => createLink('execution', 'export', "status=$status&productID=$productID&orderBy=$orderBy&from=project"),
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
    set::cols(array_values($config->projectExecution->dtable->fieldList)),
    set::data($executions),
    set::checkable($canBatchAction),
    //set::onRenderCell(jsRaw('onRenderSparkline')),
    set::canRowCheckable(jsRaw('function(rowID){return this.getRowInfo(rowID).data.isExecution == 1;}')),
    set::footToolbar($footToolbar),
    set::footPager(
        usePager(),
        set::recPerPage($pager->recPerPage),
        set::recTotal($pager->recTotal),
        set::linkCreator(helper::createLink('project', 'execution', "status={$status}&projectID=$projectID&orderBy={$orderBy}&productID={$productID}&recTotal={recTotal}&recPerPage={recPerPage}&page={page}"))
    ),
);

render();
