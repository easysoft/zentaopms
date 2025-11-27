<?php
declare(strict_types=1);
/**
 * The testcase view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

/* zin: Define the set::module('testcase') feature bar on main menu. */
featureBar
(
    set::current($type),
    set::linkParams("executionID={$executionID}&productID={$productID}&branchID={$branchID}&type={key}&param=0&moduleID={$moduleID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"),
    hasPriv('testcase', 'zerocase') ? li
    (
        set::className('nav-item'),
        a
        (
            set::href($this->createLink('testcase', 'zeroCase', "productID=$productID&branch=$branchID&orderBy=id_desc&projectID=$executionID")),
            set('data-app', $app->tab),
            set('data-id', 'zerocaseTab'),
            $lang->testcase->zeroCase
        )
    ) : null,
    li(searchToggle(set::module('executionCase'), set::open($type == 'bysearch')))
);

/* zin: Define the toolbar on main menu. */
$canCreateTestcase = hasPriv('testcase', 'create') && common::canModify('execution', $execution);
if($canCreateTestcase) $createItem = array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->testcase->create, 'url' => $this->createLink('testcase', 'create', "productID={$productID}&branch=0&moduleID=0&from=execution&param={$execution->id}"), 'data-app' => 'execution', 'id' => 'createTestCaseBtn');
$viewItems = array(array('text' => $lang->testcase->listView, 'url' => createLink('execution', 'testcase', "executionID={$executionID}&productID={$productID}&branchID={$branchID}"), 'active' => true));
if(hasPriv('testcase', 'groupcase'))
{
    $link = createLink('testcase', 'groupCase', "productID=$productID&branch=$branchID&groupBy=story&projectID=$executionID");
    $viewItems[] = array('text' => $lang->testcase->groupView, 'url' => $link, 'data-app' => $app->tab, 'active' => false);
}
toolbar
(
    $viewItems ? dropdown
    (
        btn
        (
            setClass('btn ghost square'),
            set::icon('kanban')
        ),
        set::items($viewItems),
        set::placement('bottom-end')
    ) : null,
    !empty($createItem) ? item(set($createItem)) : null
);

/* zin: Define the sidebar in main content. */
sidebar
(
    moduleMenu(set(array(
        'modules'   => $moduleTree,
        'activeKey' => $moduleID,
        'closeLink' => $this->createLink('execution', 'testcase', "executionID={$executionID}")
    )))
);

if(isset($config->testcase->dtable->fieldList['branch'])) $config->testcase->dtable->fieldList['branch']['map'] = $branchTagOption;
if(isset($config->testcase->dtable->fieldList['story']))  $config->testcase->dtable->fieldList['story']['map']  = $stories;
if(isset($config->testcase->dtable->fieldList['scene']))  $config->testcase->dtable->fieldList['scene']['map']  = $scenes;
$config->testcase->dtable->fieldList['actions']['list']['edit']['url'] = str_replace('%executionID%', (string)$executionID, $config->testcase->dtable->fieldList['actions']['list']['edit']['url']);
$config->testcase->dtable->fieldList['actions']['menu'] =  array(array('confirmStoryChange'), array('runCase|ztfRun', 'runResult', 'edit', 'createBug', 'create'));
if($config->testcase->needReview || !empty($config->testcase->forceReview)) array_unshift($config->testcase->dtable->fieldList['actions']['menu'][1], 'review');
$this->config->testcase->dtable->fieldList['title']['link'] = array('module' => 'testcase', 'method' => 'view', 'params' => "caseID={id}");
$this->config->testcase->dtable->fieldList['bugs']['link']  = array('module' => 'testcase', 'method' => 'bugs', 'params' => "runID=0&caseID={id}");
foreach($config->testcase->dtable->fieldList['actions']['list'] as $method => $action)
{
    if(isset($action['url']) && isset($action['url']['params'])) $config->testcase->dtable->fieldList['actions']['list'][$method]['url']['params'] = str_replace(array('{caseID}', '{runID}'), array('{id}', '0'), $action['url']['params']);
}

$this->loadModel('testcase');
foreach($cases as $case)
{
    initTableData(array($case), $config->testcase->dtable->fieldList, $this->testcase);

    $stages = array_filter(explode(',', $case->stage));
    foreach($stages as $key => $stage) $stages[$key] = zget($lang->testcase->stageList, $stage);
    $case->stage = implode($lang->comma, $stages);
    if(isset($case->script)) unset($case->script);
}

$cols = $this->loadModel('datatable')->getSetting('execution', 'testcase');
$cols['id']['name'] = $cols['id']['type'] = 'id';
if(isset($cols['story'])) $cols['story']['hint']  = jsRaw('(info) => info.col.setting.map[info.row.data.story]');
if(isset($cols['pri']))   $cols['pri']['priList'] = $lang->testcase->priList;

dtable
(
    set::userMap($users),
    set::cols($cols),
    set::data($cases),
    set::customCols(true),
    set::orderBy($orderBy),
    set::sortLink(createLink('execution', 'testcase', "executionID={$executionID}&productID={$productID}&branchID={$branchID}&type={$type}&param=0&moduleID={$moduleID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(
        usePager(array('linkCreator' => helper::createLink('execution', 'testcase', "executionID={$executionID}&productID={$productID}&branchID={$branchID}&type={$type}&param=0&moduleID={$moduleID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={recPerPage}&pageID={page}"))),
    ),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::modules($modulePairs),
    set::emptyTip($lang->testcase->noCase),
    set::createTip($lang->testcase->create),
    set::createLink($canCreateTestcase ? createLink('testcase', 'create', "productID={$productID}&branch=0&moduleID=0&from=execution&param={$execution->id}") : '')
);

render();
