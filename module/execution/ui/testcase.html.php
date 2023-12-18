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
    li(searchToggle(set::module('executionCase'), set::open($type == 'bysearch')))
);

/* zin: Define the toolbar on main menu. */
$canCreateTestcase = hasPriv('testcase', 'create') && common::canModify('execution', $execution);
if($canCreateTestcase) $createItem = array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->testcase->create, 'url' => $this->createLink('testcase', 'create', "productID={$productID}&branch=0&moduleID=0&from=execution&param={$execution->id}"), 'data-app' => 'execution');
toolbar
(
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
$config->testcase->dtable->fieldList['actions']['list']['edit']['url'] = str_replace('%executionID%', (string)$executionID, $config->testcase->dtable->fieldList['actions']['list']['edit']['url']);
$config->testcase->dtable->fieldList['actions']['menu'] =  array(array('confirmStoryChange'), array('runCase', 'runResult', 'edit', 'createBug', 'create'));
$this->config->testcase->dtable->fieldList['title']['link'] = array('module' => 'testcase', 'method' => 'view', 'params' => "caseID={id}");
foreach($config->testcase->dtable->fieldList['actions']['list'] as $method => $action)
{
    if(isset($action['url']) && isset($action['url']['params'])) $config->testcase->dtable->fieldList['actions']['list'][$method]['url']['params'] = str_replace('{caseID}', '{id}', $action['url']['params']);
}

$this->loadModel('testcase');
foreach($cases as $case)
{
    initTableData(array($case), $config->testcase->dtable->fieldList, $this->testcase);

    $stages = array_filter(explode(',', $case->stage));
    foreach($stages as $key => $stage) $stages[$key] = zget($lang->testcase->stageList, $stage);
    $case->stage = implode($lang->comma, $stages);
}

$cols = $config->testcase->dtable->fieldList;
unset($cols['module']);

dtable
(
    set::userMap($users),
    set::cols($cols),
    set::data($cases),
    set::orderBy($orderBy),
    set::sortLink(createLink('execution', 'testcase', "executionID={$executionID}&productID={$productID}&branchID={$branchID}&type={$type}&param=0&moduleID={$moduleID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(
        usePager(array('linkCreator' => helper::createLink('execution', 'testcase', "executionID={$executionID}&productID={$productID}&branchID={$branchID}&type={$type}&param=0&moduleID={$moduleID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={recPerPage}&pageID={page}"))),
    ),
    set::emptyTip($lang->testcase->noCase),
    set::createTip($lang->testcase->create),
    set::createLink($canCreateTestcase ? createLink('testcase', 'create', "productID={$productID}&branch=0&moduleID=0&from=execution&param={$execution->id}") : '')
);

render();
