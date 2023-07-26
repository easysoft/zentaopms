<?php
declare(strict_types=1);
/**
 * The groupcase view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('allTestcases', $lang->testcase->allTestcases);

$canCreateSuite = hasPriv('testsuite', 'create');
$canLinkCase    = hasPriv('testtask',  'linkCase');
$canExport      = hasPriv('testcase',  'export');
$canReport      = hasPriv('testtask',  'report');
$canView        = hasPriv('testtask',  'view');

/* Process variables of sutie menu. */
$suiteItems = array();
if(empty($suites))
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
    li
    (
        set::class('nav-item'),
        a
        (
            set::href(inlink('cases', "taskID=$task->id&browseType=all")),
            set('data-app', $app->tab),
            $lang->testtask->allCases
        )
    ),
    li
    (
        set::class('nav-item'),
        a
        (
            set::href(inlink('cases', "taskID=$task->id&browseType=assignedtome")),
            set('data-app', $app->tab),
            $lang->testtask->assignedToMe
        )
    )
);

$viewItems = array(array('text' => $lang->testcase->listView, 'url' => inlink('browse', "productID=$productID&branch=$branch&browseType=all"), 'active' => $rawMethod != 'groupcase' ? true : false));
toolbar
(
    dropdown
    (
        btn
        (
            setClass('btn btn-link ghost square'),
            set::icon('kanban')
        ),
        set::items($viewItems),
        set::placement('bottom-end'),
    ),
    set::items
    (
        array
        (
            $canLinkCase ? array('class' => 'ghost', 'icon' => 'link', 'text' => $lang->testtask->linkCase, 'url' => inlink('linkCase', "taskID=$task->id")) : null,
            $canExport ? array('class' => 'ghost', 'icon' => 'export', 'text' => $lang->export, 'url' => $this->createLink('testcase', 'export', "productID=$productID&orderBy=case_desc&taskID=$task->id"), 'data-toggle' => 'modal') : null,
            $canReport ? array('class' => 'ghost', 'icon' => 'bar-chart', 'text' => $lang->testtask->report->common, 'url' => inlink('report', "productID=$productID&taskID=$task->id&browseType=$browseType&branchID=$task->branch&moduleID=" . (empty($moduleID) ? '' : $moduleID))) : null,
            $canView ? array('class' => 'ghost', 'icon' => 'list-alt', 'text' => $lang->testtask->view, 'url' => inlink('view', "taskID=$task->id")) : null,
            array('class' => 'ghost', 'icon' => 'back', 'text' => $lang->goback, 'url' => $this->session->testtaskList)
        )
    )
);

$config->testcase->actionList['runCase']['url']    = array('module' => 'testtask', 'method' => 'runCase', 'params' => 'runID={id}&caseID={case}&version={version}');
$config->testcase->actionList['edit']['url']       = array('module' => 'testcase', 'method' => 'edit', 'params' => 'caseID={case}&comment=false&executionID=%executionID%');

$config->testcase->group->dtable->fieldList['actions']['list']         = $config->testcase->actionList;
$config->testcase->group->dtable->fieldList['id']['name']              = 'case';
$config->testcase->group->dtable->fieldList['actions']['menu']         = array('runCase', 'edit', 'unlinkCase');
$config->testcase->group->dtable->fieldList['title']['link']['params'] = 'caseID={case}';

$runs = initTableData($runs, $config->testcase->group->dtable->fieldList);

dtable
(
    set::id('groupCaseTable'),
    set::userMap($users),
    set::cols($config->testcase->group->dtable->fieldList),
    set::data($runs),
    set::plugins(array('cellspan')),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::getCellSpan(jsRaw('window.getCellSpan')),
);

render();
