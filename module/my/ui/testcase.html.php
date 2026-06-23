<?php
declare(strict_types=1);
/**
 * The testcase view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

jsVar('unexecuted', $lang->testcase->unexecuted);

featureBar
(
    set::current($browseType),
    set::linkParams("mode=testcase&browseType={key}&param=&orderBy={$orderBy}"),
    li(searchToggle(set::module($this->app->rawMethod . 'Testcase'), set::open($browseType == 'bysearch')))
);

$canBatchEdit   = common::hasPriv('testcase', 'batchEdit');
$canBatchRun    = common::hasPriv('testtask', 'batchRun') && $app->rawMethod == 'work';
$canBatchAction = ($canBatchEdit || $canBatchRun);

$footToolbar = null;
if($canBatchAction)
{
    $footToolbar    = array('items' => array
    (
        $canBatchEdit ? array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => helper::createLink('testcase', 'batchEdit', "productID=0&branch=all&type=case&from={$app->rawMethod}")) : null,
        $canBatchRun  ? array('text' => $lang->testtask->runCase, 'className' => 'batch-btn batch-run not-open-url', 'data-url' => helper::createLink('testtask', 'batchRun', "productID=0&orderBy=id_desc&from={$app->rawMethod}")) : null
    ), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));
}

if($browseType == 'openedbyme' || $app->rawMethod == 'contribute')
{
    unset($config->my->testcase->dtable->fieldList['taskName']);
    unset($config->my->testcase->dtable->fieldList['openedBy']);
}

if($browseType == 'assigntome')
{
    $config->my->testcase->dtable->fieldList['title']['link']['params'] .= "&from=testtask&taskID={task}";
    $config->my->testcase->dtable->fieldList['actions']['list']['runCase']['url']   = array('module' => 'testtask', 'method' => 'runCase',   'params' => 'id={run}');
    $config->my->testcase->dtable->fieldList['actions']['list']['runResult']['url'] = array('module' => 'testtask', 'method' => 'results',   'params' => 'id={run}');
    $config->my->testcase->dtable->fieldList['actions']['list']['createBug']['url'] = array('module' => 'testcase', 'method' => 'createBug', 'params' => 'product={product}&caseID={case}&version={version}&runID={run}');
    $config->my->testcase->dtable->fieldList['actions']['menu'] = array('runCase', 'runResult', 'edit', 'createBug', 'create');
}
foreach($config->my->testcase->dtable->fieldList['actions']['list'] as &$action) $action['url']['params'] = str_replace(array('{caseID}', '%executionID%', '{runID}'), array('{id}', '0', '0'), $action['url']['params']);

$products = $this->loadModel('product')->getPairs('', 0, '', 'all');
$config->my->testcase->dtable->fieldList['product']['map'] = $products;

$cols  = $this->loadModel('datatable')->getSetting('my', 'testcase');
$cases = initTableData($cases, $cols, $this->testcase);
$data  = array_values($cases);

$defaultSummary = sprintf($lang->testcase->failSummary, count($cases), $failCount);
dtable
(
    set::data($data),
    set::cols($cols),
    set::customCols(true),
    set::userMap($users),
    set::rowKey('run'),
    set::checkable(true),
    set::defaultSummary(array('html' => $defaultSummary)),
    set::checkedSummary($lang->testcase->failCheckedSummary),
    set::checkInfo(jsRaw('function(checkedIDList){return window.setStatistics(this, checkedIDList);}')),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::emptyTip($lang->testcase->noCase)
);

render();
