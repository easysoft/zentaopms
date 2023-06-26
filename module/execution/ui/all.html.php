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
            $statusList[] = array('text' => $value, 'class' => 'batch-btn', 'data-url' => createLink('execution', 'batchChangeStatus', "status=$key&executionID={$execution->id}"));
        }

        menu
        (
            set::id('navStatus'),
            set::class('dropdown-menu'),
            set::items($statusList)
        );
    }
}

$executions = $this->execution->generateRow($executionStats, $users, $avatarList, $productID);
$tableData  = initTableData($executions, $config->execution->dtable->fieldList, $this->execution);

/* zin: Define the feature bar on main menu. */
featureBar
(
    set::current($status),
    li(searchToggle())
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
        'url'   => createLink('execution', 'create'),
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
    set::footPager(
        usePager(),
        set::recPerPage($recPerPage),
        set::recTotal($recTotal),
        set::linkCreator(helper::createLink('execution', 'all', "status={$status}&orderBy={$orderBy}&productID={$productID}&param=$param&recTotal={$recTotal}&recPerPage={recPerPage}&page={page}"))
    ),
);

render();
