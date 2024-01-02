<?php
declare(strict_types=1);
/**
 * The cases view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

$app->loadLang('zanode');

jsVar('automation',     !empty($automation) ? $automation->id : 0);
jsVar('runCaseConfirm', $lang->zanode->runCaseConfirm);

$canCreateSuite = hasPriv('testsuite', 'create');
$canGroupCase   = hasPriv('testtask', 'groupcase');
$canLinkCase    = hasPriv('testtask', 'linkCase');
$canExport      = hasPriv('testcase', 'export');
$canReport      = hasPriv('testtask', 'report');
$canView        = hasPriv('testtask', 'view');
$canBatchEdit   = hasPriv('testcase', 'batchEdit');
$canBatchUnlink = hasPriv('testtask', 'batchUnlinkCases');
$canBatchAssign = hasPriv('testtask', 'batchAssign');
$canBatchRun    = hasPriv('testtask', 'batchRun');
$canBatchAction = ($canBeChanged && ($canBatchEdit || $canBatchUnlink || $canBatchAssign || $canBatchRun));

$closeLink = $browseType == 'bymodule' ? inlink('cases', "taskID={$task->id}&browseType={$browseType}&param=0&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("taskCaseModule")';
sidebar
(
    moduleMenu(set(array
    (
        'modules'   => $moduleTree,
        'activeKey' => $moduleID,
        'closeLink' => $closeLink,
        'app'       => $app->tab
    )))
);

/* Process variables of sutie menu. */
$suiteItems = array();
if(empty($suites))
{
    if($canCreateSuite && (empty($productID) || common::canModify('product', $product)))
    {
        $suiteItems[] = array('text' => $lang->testsuite->create, 'url' => $this->createLink('testsuite', 'create', "productID={$productID}"), 'data-app' => $app->tab);
    }
}
else
{
    foreach($suites as $id => $name)
    {
        $suiteItems[] = array('text' => $name, 'url' => inlink('cases', "taskID={$task->id}&browseType=bySuite&param={$id}"), 'active' => $id == $param, 'data-app' => $app->tab);
    }
}

featureBar
(
    set::linkParams("taskID={$task->id}&browseType={key}&param=0"),
    dropdown
    (
        btn
        (
            setClass('ghost'),
            $suiteName
        ),
        set::items($suiteItems)
    ),
    li(searchToggle(set::open($browseType == 'bysearch')))
);

$viewItems   = array();
$viewItems[] = array('text' => $lang->testcase->listView,  'url' => inlink('cases',     "taskID={$task->id}"),               'active' => true, 'data-app' => $app->tab);
$viewItems[] = array('text' => $lang->testcase->groupView, 'url' => inlink('groupCase', "taskID={$task->id}&groupBy=story"), 'active' => false, 'data-app' => $app->tab);
toolbar
(
    dropdown
    (
        btn
        (
            setClass('btn ghost square'),
            set::icon('kanban')
        ),
        set::items($viewItems),
        set::placement('bottom-end')
    ),
    $canLinkCase ? btn
    (
        setClass('ghost'),
        set::icon('link'),
        set::url(inlink('linkCase', "taskID={$task->id}")),
        set('data-app', $app->tab),
        $lang->testtask->linkCase
    ) : null,
    $canExport ? btn
    (
        setClass('ghost'),
        set::icon('export'),
        set('data-toggle', 'modal'),
        set::url($this->createLink('testcase', 'export', "productID={$productID}&orderBy=case_desc&taskID={$task->id}")),
        $lang->export
    ) : null,
    $canReport ? btn
    (
        setClass('ghost'),
        set::icon('bar-chart'),
        set::url(inlink('report', "productID={$productID}&taskID={$task->id}&browseType={$browseType}&branchID={$task->branch}&moduleID=" . (empty($moduleID) ? '' : $moduleID))),
        set('data-app', $app->tab),
        $lang->testtask->report->common
    ) : null,
    $canView ? btn
    (
        setClass('ghost'),
        set::icon('list-alt'),
        set::url(inlink('view', "taskID={$task->id}")),
        set('data-app', $app->tab),
        $lang->testtask->view
    ) : null,
    btn(set::icon('back'), setClass('ghost'), set::url($this->session->testtaskList), $lang->goback)
);

$footToolbar = null;
if($canBatchAction)
{
    $footToolbar = array('items' => array());
    if($canBatchEdit && $canBatchUnlink)
    {
        $footToolbar['items'][] = array('type' => 'btn-group', 'items' => array
        (
            array('text' => $lang->edit, 'className' => 'batch-btn not-open-url secondary', 'data-url' => helper::createLink('testcase', 'batchEdit', "productID={$productID}&branch=all")),
            array('caret' => 'up', 'className' => 'secondary', 'items' => array(array('text' => $lang->unlink, 'innerClass' => 'batch-btn not-open-url ajax-btn', 'data-url' => inlink('batchUnlinkCases', "taskID={$task->id}"))), 'data-placement' => 'top-start')
        ));
    }
    if($canBatchEdit && !$canBatchUnlink) $footToolbar['items'][] = array('text' => $lang->edit, 'className' => 'batch-btn not-open-url', 'btnType' => 'secondary', 'data-url' => $this->createLink('testcase', 'batchEdit', "productID={$productID}&branch=all"));
    if(!$canBatchEdit && $canBatchUnlink) $footToolbar['items'][] = array('text' => $lang->unlink, 'className' => 'batch-btn not-open-url', 'btnType' => 'secondary', 'data-url' => inlink('batchUnlinkCases', "taskID={$task->id}"));
    if($canBatchAssign)
    {
        $userItems = array();
        foreach($assignedToList as $account => $realname) $userItems[] = array('text' => $realname, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => inlink('batchAssign', "taskID={$task->id}&account={$account}"));

        $footToolbar['items'][] = array('text' => $lang->testtask->assign, 'caret' => 'up', 'btnType' => 'secondary', 'type' => 'dropdown', 'items' => $userItems, 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true));
    }
    if($canBatchRun) $footToolbar['items'][] = array('text' => $lang->testtask->runCase, 'className' => 'batch-btn batch-run not-open-url', 'btnType' => 'secondary', 'data-url' => inlink('batchRun', "productID={$productID}&orderBy=id_desc&from=testtask&taskID={$task->id}&confirm=yes"));
}

$cols = $this->loadModel('datatable')->getSetting('testtask');
if(isset($cols['id']['name'])) $cols['id']['name'] = 'case';
if(isset($cols['title']['link']['params'])) $cols['title']['link']['params'] = 'caseID={case}';
if(isset($cols['bugs']['link']['params'])) $cols['bugs']['link']['params'] = 'caseID={case}';
if(isset($cols['scene'])) $cols['scene']['map'] = $iscenes;

$runs = initTableData($runs, $cols);

dtable
(
    set::customCols(true),
    set::userMap($users),
    set::cols($cols),
    set::data($runs),
    set::orderBy($orderBy),
    set::sortLink(createLink('testtask', 'cases', "taskID={$task->id}&browseType={$browseType}&param={$param}&order={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::checkable($canBatchAction),
    set::fixedLeftWidth('44%'),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::customData(array('modules' => $modulePairs))
);

render();
