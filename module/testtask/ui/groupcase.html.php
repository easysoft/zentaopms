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

featureBar
(
    set::linkParams("taskID={$task->id}&browseType={key}"),
);

$viewItems   = array();
$viewItems[] = array('text' => $lang->testcase->listView,  'url' => inlink('cases',     "taskID={$task->id}"),               'active' => false, 'data-app' => $app->tab);
$viewItems[] = array('text' => $lang->testcase->groupView, 'url' => inlink('groupCase', "taskID={$task->id}&groupBy=story"), 'active' => true,  'data-app' => $app->tab);
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
        set::className('ghost'),
        set::icon('link'),
        set::url(inlink('linkCase', "taskID=$task->id")),
        $lang->testtask->linkCase
    ) : null,
    $canExport ? btn
    (
        set::className('ghost'),
        set::icon('export'),
        set::url($this->createLink('testcase', 'export', "productID=$productID&orderBy=case_desc&taskID=$task->id")),
        $lang->export
    ) : null,
    $canReport ? btn
    (
        set::className('ghost'),
        set::icon('bar-chart'),
        set::url(inlink('report', "productID=$productID&taskID=$task->id&browseType=group&branchID=$task->branch&moduleID=" . (empty($moduleID) ? '' : $moduleID))),
        $lang->testtask->report->common
    ) : null,
    $canView ? btn
    (
        set::className('ghost'),
        set::icon('list-alt'),
        set::url(inlink('view', "taskID=$task->id")),
        $lang->testtask->view
    ) : null,
    btn(set::icon('back'), set::className('ghost'), set::url($this->session->testtaskList), $lang->goback)
);

$config->testcase->actionList['runCase']['url']    = array('module' => 'testtask', 'method' => 'runCase', 'params' => 'runID={id}&caseID={case}&version={version}');
$config->testcase->actionList['edit']['url']       = array('module' => 'testcase', 'method' => 'edit', 'params' => 'caseID={case}&comment=false&executionID=%executionID%');

$config->testcase->group->dtable->fieldList['actions']['list']         = $config->testcase->actionList;
$config->testcase->group->dtable->fieldList['id']['name']              = 'case';
$config->testcase->group->dtable->fieldList['actions']['menu']         = array('runCase', 'edit', 'unlinkCase');
$config->testcase->group->dtable->fieldList['title']['link']['params'] = 'caseID={case}';
$config->testcase->group->dtable->fieldList['bugs']['link']['params']  = 'runID={id}&caseID={case}';

foreach($config->testcase->group->dtable->fieldList as $colName => $col) $cols[$colName]['sortType'] = false;

$cases = initTableData($cases, $config->testcase->group->dtable->fieldList);

dtable
(
    set::id('groupCaseTable'),
    set::userMap($users),
    set::cols($config->testcase->group->dtable->fieldList),
    set::data($cases),
    set::plugins(array('cellspan')),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::getCellSpan(jsRaw('window.getCellSpan'))
);

render();
