<?php
declare(strict_types=1);
/**
 * The cases view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

sidebar
(
    moduleMenu(set(array
    (
        'modules'   => $moduleTree,
        'activeKey' => $currentModuleID,
        'closeLink' => $closeLink
    )))
);

featureBar
(
    li(searchToggle())
);

toolbar
(
    common::hasPriv('testtask', 'linkCase') ? item(set(array
    (
        'icon'  => 'link',
        'text'  => $lang->testtask->linkCase,
        'class' => 'ghost',
        'url'   => createLink('testtask', 'linkCase', "taskID={$task->id}")
    ))) : null,
    common::hasPriv('testcase', 'export') ? item(set(array
    (
        'icon'  => 'export',
        'text'  => $lang->export,
        'class' => 'ghost',
        'url'   => createLink('testcase', 'export', "productID={$productID}&orderBy=case_desc&taskID={$task->id}")
    ))) : null,
    common::hasPriv('testtask', 'report') ? item(set(array
    (
        'icon'  => 'bar-chart',
        'text'  => $lang->testtask->report->common,
        'class' => 'ghost',
        'url'   => createLink('testtask', 'report', "productID={$productID}&taskID={$task->id}&browseType={$browseType}&branchID={$task->branch}&moduleID=" . (empty($moduleID) ? '' : $moduleID))
    ))) : null,
    common::hasPriv('testtask', 'view') ? item(set(array
    (
        'icon'  => 'list-alt',
        'text'  => $lang->testtask->view,
        'class' => 'ghost',
        'url'   => createLink('testtask', 'view', "taskID=$task->id")
    ))) : null,
    item(set(array
    (
        'icon'  => 'back',
        'text'  => $lang->goback,
        'class' => 'ghost',
        'url'   => createLink('testtask', 'browse', "taskID=$task->id")
    ))),
);

$footToolbar = array('items' => array
(
    array('type' => 'btn-group', 'items' => array
    (
        array('text' => $lang->edit, 'className' => 'batch-btn secondary', 'data-url' => createLink('testcase', 'batchEdit', "productID={$productID}&branch=all")),
        array('caret' => 'up', 'className' => 'secondary ' . (common::hasPriv('testtask', 'batchUnlinkCases') ? '' : 'hidden'), 'url' => '#navActions', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    )),
    array('caret' => 'up', 'text' => $lang->testtask->assignedTo, 'className' => common::hasPriv('testtask', 'batchAssign') ? '' : 'hidden', 'url' => '#navAssignedTo', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    array('text' => $lang->testtask->runCase, 'className' => 'batch-btn ajax-btn ' . (common::hasPriv('testtask', 'batchRun') ? '' : 'hidden'), 'data-url' => helper::createLink('testtask', 'batchRun', "productID={$productID}&orderBy=id_desc&from=testtask&taskID={$taskID}&confirm=yes"))
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

menu
(
    set::id('navActions'),
    set::class('menu dropdown-menu'),
    set::items(array
    (
        array('text' => $lang->testtask->unlinkCase, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testtask', 'batchUnlinkCases', "taskID={$task->id}")),
    ))
);

$assignedToItems = array();
foreach($assignedToList as $key => $value) $assignedToItems[] = array('text' => $value, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testtask', 'batchAssign', "taskID={$task->id}"));

menu
(
    set::id('navAssignedTo'),
    set::class('dropdown-menu'),
    set::items($assignedToItems)
);

$config->testtask->testcase->dtable->fieldList['story']['map'] = $stories;
$runs = initTableData($runs, $config->testtask->testcase->dtable->fieldList, $this->testcase);
$cols = array_values($config->testtask->testcase->dtable->fieldList);
$data = array_values($runs);

dtable
(
    set::customCols(true),
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::checkable(true),
    set::fixedLeftWidth('44%'),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
);

render();
