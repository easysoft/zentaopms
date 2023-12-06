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
            array('text' => $lang->edit, 'className' => "btn secondary size-sm {$editClass}", 'data-url' => createLink('execution', 'batchEdit'))
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

$executions = $this->execution->generateRow($executionStats, $users, $avatarList, $productID);
$tableData  = initTableData($executions, $config->execution->dtable->fieldList, $this->execution);

/* zin: Define the feature bar on main menu. */
featureBar
(
    set::current($status),
    set::linkParams("status={key}"),
    li(searchToggle(set::open($status == 'bySearch')))
);

/* zin: Define the toolbar on main menu. */
toolbar
(
    hasPriv('execution', 'export') ? item(set(array
    (
        'icon'        => 'export',
        'text'        => $lang->programplan->exporting,
        'class'       => "ghost export",
        'url'         => createLink('execution', 'export', "status=$status&productID=$productID&orderBy=$orderBy&from=$from"),
        'data-toggle' => 'modal'
    ))) : null,
    hasPriv('execution', 'create') ? item(set(array
    (
        'icon' => 'plus',
        'text' => $lang->execution->createExec,
        'class' => "primary create-execution-btn",
        'url'   => createLink('execution', 'create')
    ))) : null
);

$setting = $this->datatable->getSetting('execution');

dtable
(
    set::userMap($users),
    set::cols($setting),
    set::data($tableData),
    set::checkable($canBatchAction),
    set::fixedLeftWidth('44%'),
    set::customCols(true),
    set::footToolbar($footToolbar),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::orderBy($orderBy),
    set::sortLink(createLink('execution', 'all', "status={$status}&orderBy={name}_{sortType}&productID={$productID}&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&page={$pager->pageID}")),
    set::footPager(usePager(array
    (
        'linkCreator' => helper::createLink('execution', 'all', "status={$status}&orderBy={$orderBy}&productID={$productID}&param=$param&recTotal={recTotal}&recPerPage={recPerPage}&page={page}")
    ))),
    set::emptyTip($from == 'execution' ? $lang->execution->noExecutions : $lang->execution->noExecution),
    set::createTip($from == 'execution' ? $lang->execution->createExec : $lang->execution->create),
    set::createLink(hasPriv('execution', 'create') ? createLink('execution', 'create') : '')
);

render();
