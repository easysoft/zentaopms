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

$closeLink = inlink('cases', "taskID={$task->id}&browseType=bymodule&param=0&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}");
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
        $suiteItems[] = array('text' => $lang->testsuite->create, 'url' => $this->createLink('testsuite', 'create', "productID={$productID}"), 'data-app' => 'qa');
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
$viewItems[] = array('text' => $lang->testcase->listView,  'url' => inlink('cases',     "taskID={$task->id}"),                          'active' => true,  'data-app' => $app->tab);
$viewItems[] = array('text' => $lang->testcase->groupView, 'url' => inlink('groupCase', "taskID={$task->id}&browseType={$browseType}"), 'active' => false, 'data-app' => $app->tab);
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
        setClass('ghost linkCase-btn'),
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
        foreach($assignedToList as $account => $realname) $userItems[] = array('text' => $realname, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => inlink('batchAssign', "taskID={$task->id}"), 'data-account' => $account);

        $footToolbar['items'][] = array('text' => $lang->testtask->assign, 'caret' => 'up', 'btnType' => 'secondary', 'type' => 'dropdown', 'items' => $userItems, 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true));
    }
    if($canBatchRun) $footToolbar['items'][] = array('text' => $lang->testtask->runCase, 'className' => 'batch-btn batch-run not-open-url', 'btnType' => 'secondary', 'data-url' => inlink('batchRun', "productID={$productID}&orderBy=id_desc&from=testtask&taskID={$task->id}&confirm=yes"));
}

$cols = $this->loadModel('datatable')->getSetting('testtask');
if(isset($cols['id']['name']))    $cols['id']['name']    = 'case';
if(isset($cols['story']['name'])) $cols['story']['name'] = 'storyTitle';
if(isset($cols['title']) && !isset($cols['id'])) $cols['title']['checkbox']  = true;
if(isset($cols['title']['link']['params'])) $cols['title']['link']['params'] = 'caseID={case}&version={version}&from=testtask&taskID=' . $task->id;
if(isset($cols['bugs']['link']['params'])) $cols['bugs']['link']['params'] = 'id={id}';
if(isset($cols['scene'])) $cols['scene']['map'] = $iscenes;
if(isset($cols['status'])) $cols['status']['statusMap']['changed'] = $lang->testcase->changed;
if(isset($cols['title'])) $cols['title']['nestedToggle'] = true;
if(isset($cols['pri'])) $cols['pri']['priList'] = $lang->testcase->priList;
if(isset($cols['branch'])) $cols['branch']['map'] = $branches;
if(isset($cols['stage'])) $cols['stage']['map'] = $lang->testcase->stageList;

$runs = initTableData($runs, $cols);
$runs = array_map(
    function($run)
    {
        if(isset($run->version) && isset($run->caseVersion) && $run->version < $run->caseVersion) $run->status = 'changed';
        if($run->isScene) unset($run->actions);
        return $run;
    },
    $runs
);

dtable
(
    set::customCols(true),
    set::userMap($users),
    set::nested(true),
    set::cols($cols),
    set::data($runs),
    set::orderBy($orderBy),
    set::sortLink(createLink('testtask', 'cases', "taskID={$task->id}&browseType={$browseType}&param={$param}&order={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::checkable($canBatchAction),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::customData(array('modules' => $modulePairs))
);

render();
