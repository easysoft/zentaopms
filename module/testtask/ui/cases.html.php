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

$closeLink = $browseType == 'bymodule' ? inlink('cases', "taskID=$task->id&browseType=$browseType&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("taskCaseModule")';
sidebar
(
    moduleMenu(set(array
    (
        'modules'   => $moduleTree,
        'activeKey' => $moduleID,
        'closeLink' => $closeLink
    )))
);

/* Process variables of sutie menu. */
$suiteItems = array();
if(empty($suiteList))
{
    if($canCreateSuite && (empty($productID) || common::canModify('product', $product)))
    {
        $suiteItems[] = array('text' => $lang->testsuite->create, 'url' => $this->createLink('testsuite', 'create', "productID=$productID"));
    }
}
else
{
    foreach($suites as $id => $name)
    {
        $suiteItems[] = array('text' => $name, 'url' => inlink('cases', "taskID=$task->id&browseType=bySuite&param=$id"), 'active' => $name == $suiteName);
    }
}

featureBar
(
    set::linkParams("taskID=$task->id&browseType={key}&param=0"),
    $canGroupCase ? li
    (
        set::class('nav-item'),
        a
        (
            set::href(inlink('groupCase', "taskID=$task->id&groupBy=story")),
            set('data-app', $app->tab),
            $lang->testcase->groupByStories
        )
    ) : null,
    dropdown
    (
        btn
        (
            setClass('ghost'),
            $suiteName
        ),
        set::items($suiteItems)
    ),
    li(searchToggle(set::open($browseType == 'bysearch'))),
    li(btn(setClass('ghost'), set::icon('unfold-all'), $lang->sort))
);

toolbar
(
    set::items
    (
        array
        (
            $canLinkCase ? array('class' => 'ghost', 'icon' => 'link', 'text' => $lang->testtask->linkCase, 'url' => inlink('linkCase', "taskID=$task->id")): null,
            $canExport   ? array('class' => 'ghost', 'icon' => 'export', 'text' => $lang->export, 'url' => $this->createLink('testcase', 'export', "productID=$productID&orderBy=case_desc&taskID=$task->id"), 'data-toggle' => 'modal') : null,
            $canReport   ? array('class' => 'ghost', 'icon' => 'bar-chart', 'text' => $lang->testtask->report->common, 'url' => "productID=$productID&taskID=$task->id&browseType=$browseType&branchID=$task->branch&moduleID=" . (empty($moduleID) ? '' : $moduleID)): null,
            $canView     ? array('class' => 'ghost', 'icon' => 'list-alt', 'text' => $lang->testtask->view, 'url' => inlink('view', "taskID=$task->id")) : null,
            array('class' => 'ghost', 'icon' => 'back', 'text' => $lang->goback, 'url' => $this->session->testtaskList)
        )
    )
);

$footToolbar = null;
if($canBatchAction)
{
    $footToolbar = array('items' => array());
    if($canBatchEdit && $canBatchUnlink)
    {
        zui::menu
        (
            set::id('navActions'),
            set::class('menu dropdown-menu'),
            set::items(array
            (
                array('text' => $lang->unlink, 'class' => 'batch-btn ajax-btn', 'data-url' => inlink('batchUnlinkCases', "taskID=$task->id"))
            ))
        );
        $footToolbar['items'][] = array('type' => 'btn-group', 'items' => array
        (
            array('text' => $lang->edit, 'className' => 'batch-btn secondary', 'data-url' => helper::createLink('testcase', 'batchEdit', "productID=$productID&branch=all")),
            array('caret' => 'up', 'className' => 'secondary', 'url' => '#navActions', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start')
        ));
    }
    if($canBatchEdit && !$canBatchUnlink) $footToolbar['items'][] = array('text' => $lang->edit, 'className' => 'batch-btn', 'btnType' => 'secondary', 'data-url' => $this->createLink('testcase', 'batchEdit', "productID=$productID&branch=all"));
    if(!$canBatchEdit && $canBatchUnlink) $footToolbar['items'][] = array('text' => $lang->unlink, 'className' => 'batch-btn', 'btnType' => 'secondary', 'data-url' => inlink('batchUnlinkCases', "taskID=$task->id"));
    if($canBatchAssign)
    {
        $userItems = array();
        foreach($assignedToList as $account => $realname) $userItems[] = array('text' => $realname, 'class' => 'batch-btn ajax-btn', 'data-url' => inlink('batchAssign', "taskID=$task->id"));

        zui::menu
        (
            set::id('navUser'),
            set::class('dropdown-menu'),
            set::items($userItems)
        );

        $footToolbar['items'][] = array('text' => $lang->testtask->assign, 'caret' => 'up', 'btnType' => 'secondary', 'url' => '#navUser', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start');
    }
    if($canBatchRun) $footToolbar['items'][] = array('text' => $lang->testtask->runCase, 'className' => 'batch-btn batch-run', 'btnType' => 'secondary', 'data-url' => inlink('batchRun', "productID=$productID&orderBy=id_desc&from=testtask&taskID=$task->id&confirm=yes"));
}

$cols = $this->loadModel('datatable')->getSetting('testtask');
if(!empty($cols['actions']['list']['createBug']['url']))
{
    $url = $cols['actions']['list']['createBug']['url'];
    $cols['actions']['list']['createBug']['url'] = str_replace(array('%execution%', '%build%', '%testtask%'), array((string)$task->execution, (string)$task->build, (string)$task->id), $url);
}

$runs = initTableData($runs, $cols);

dtable
(
    set::customCols(true),
    set::userMap($users),
    set::cols($cols),
    set::data($runs),
    set::checkable($canBatchAction),
    set::fixedLeftWidth('44%'),
    set::footToolbar($footToolbar),
    set::footPager(usePager())
);

render();
