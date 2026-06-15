<?php
declare(strict_types=1);
/**
 * The bug view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

$testcaseTitle = "[" . $lang->testcase->common . "#{case}]";
$testcaseLink = createLink('testcase', 'view', "caseID={case}&version={caseVersion}");

jsVar('testcaseTitle', $testcaseTitle);
jsVar('testcaseLink', $testcaseLink);
jsVar('checkedSummary', isset($checkedSummary) ? $checkedSummary : '');

featurebar
(
    set::current($browseType),
    set::linkParams("mode=bug&browseType={key}&param=&orderBy={$orderBy}"),
    li(searchToggle(set::module($this->app->rawMethod . 'Bug'), set::open($browseType == 'bysearch')))
);

$canBatchEdit     = common::hasPriv('bug', 'batchEdit')    && $browseType == 'assignedTo';
$canBatchConfirm  = common::hasPriv('bug', 'batchConfirm') && $browseType != 'closedBy';
$canBatchClose    = common::hasPriv('bug', 'batchClose')   && strtolower($browseType) != 'closedby';
$canBatchAssignTo = common::hasPriv('bug', 'batchAssignTo');
$canBatchAction   = $canBatchEdit || $canBatchConfirm || $canBatchClose || $canBatchAssignTo;

$currentBrowseType = $browseType;
if($browseType == 'bysearch') $browseType = $this->session->myBugType;

if($browseType == 'openedBy')
{
    unset($config->my->bug->dtable->fieldList['openedBy'], $config->my->bug->dtable->fieldList['openedDate'], $config->my->bug->dtable->fieldList['assignedDate']);
}

if($browseType == 'resolvedBy')
{
    unset($config->my->bug->dtable->fieldList['openedDate'], $config->my->bug->dtable->fieldList['resolvedBy']);
}

if($browseType == 'assignedBy') unset($config->my->bug->dtable->fieldList['openedDate']);
if($browseType == 'closedBy')   unset($config->my->bug->dtable->fieldList['openedDate']);
if($browseType == 'assignedTo') unset($config->my->bug->dtable->fieldList['assignedTo']);
if($app->rawMethod == 'work')
{
    unset($config->my->bug->dtable->fieldList['status'], $config->my->bug->dtable->fieldList['openedDate']);
}
else
{
    unset($config->my->bug->dtable->fieldList['deadline']);
}

if(!$canBatchAction) $config->my->bug->dtable->fieldList['id']['type'] = 'id';

$projectBrowseLink = createLink('project', 'browse');
$productLink       = explode('-', $config->productLink);
$productParam      = $config->productLink == 'product-all' ? '' : "productID={product}";
$productBrowseLink = createLink('product', $productLink[1], $productParam);
$config->my->bug->dtable->fieldList['product']['link'] = 'RAWJS<function(info){ if(info.row.data.shadow) return \'' . $projectBrowseLink . '\'; else return \'' . $productBrowseLink . '\'; }>RAWJS';

$storyIdList = $taskIdList = $productIdList = array();
if($config->edition != 'open')
{
    $bugRelatedObjectList = $this->loadModel('custom')->getRelatedObjectList(array_keys($bugs), 'bug', 'byRelation', true);
}

foreach($bugs as $bug)
{
    $bug->canBeChanged = common::canBeChanged('bug', $bug);
    if($bug->story)   $storyIdList[$bug->story]     = $bug->story;
    if($bug->task)    $taskIdList[$bug->task]       = $bug->task;
    if($bug->toTask)  $taskIdList[$bug->toTask]     = $bug->toTask;
    if($bug->product) $productIdList[$bug->product] = $bug->product;
    if(!$bug->project)   $bug->project = '';
    if(!$bug->execution) $bug->execution = '';
    if(!$bug->plan)      $bug->plan = '';
}

$products        = $this->loadModel('product')->getPairs('', 0, '', 'all');
$projectPairs    = $this->loadModel('project')->getPairsByProgram();
$executions      = $this->loadModel('execution')->getPairs(0, 'all');
$stories         = $this->loadModel('story')->getPairsByList($storyIdList);
$tasks           = $this->loadModel('task')->getPairsByIdList($taskIdList);
$branchTagOption = $this->loadModel('branch')->getAllPairs();
$plans           = $productIdList ? $this->loadModel('productplan')->getPairs(array_values($productIdList)) : array();

if(isset($config->my->bug->dtable->fieldList['project']))        $config->my->bug->dtable->fieldList['project']['map']   = $projectPairs;
if(isset($config->my->bug->dtable->fieldList['execution']))      $config->my->bug->dtable->fieldList['execution']['map'] = $executions;
if(isset($config->my->bug->dtable->fieldList['plan']))           $config->my->bug->dtable->fieldList['plan']['map']      = $plans;
if(isset($config->my->bug->dtable->fieldList['task']))           $config->my->bug->dtable->fieldList['task']['map']      = $tasks;
if(isset($config->my->bug->dtable->fieldList['toTask']))         $config->my->bug->dtable->fieldList['toTask']['map']    = $tasks;
if(isset($config->my->bug->dtable->fieldList['story']))          $config->my->bug->dtable->fieldList['story']['map']     = $stories;
if(isset($config->my->bug->dtable->fieldList['product']))        $config->my->bug->dtable->fieldList['product']['map']   = $products;

$pinyinItems     = common::convert2Pinyin($memberPairs);
$assignedToItems = array();
foreach ($memberPairs as $key => $value)
{
    if(!$key) continue;
    $key = base64_encode((string)$key); // 编码用户名中的特殊字符
    $assignedToItems[] = array('text' => $value, 'keys' => zget($pinyinItems, $value, ''), 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => createLink('bug', 'batchAssignTo', "assignedTo=$key&productID=0&type=my"));
}

$footToolbar = $canBatchAction ? array('items' => array
(
    array('text' => $lang->edit, 'className' => 'batch-btn ' . ($canBatchEdit ? '' : 'hidden'), 'data-url' => createLink('bug', 'batchEdit')),
    array('text' => $lang->confirm, 'className' => 'batch-btn ajax-btn ' . ($canBatchConfirm ? '' : 'hidden'), 'data-url' => createLink('bug', 'batchConfirm')),
    array('text' => $lang->close, 'className' => 'batch-btn ajax-btn ' . ($canBatchClose ? '' : 'hidden'), 'data-url' => createLink('bug', 'batchClose')),
    array('text' => $lang->bug->assignedTo, 'className' => ($canBatchAssignTo ? '' : 'hidden'), 'type' => 'dropdown', 'items' => $assignedToItems, 'caret' => 'up', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)),
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary')) : null;

$cols = $this->loadModel('datatable')->getSetting('my', 'bug');
foreach($cols as $colName => $col)
{
    if(!isset($col['sortType'])) $cols[$colName]['sortType'] = true;
}

$bugs = initTableData($bugs, $cols, $this->bug);

dtable
(
    set::cols($cols),
    set::data(array_values($bugs)),
    set::priList($lang->bug->priList),
    set::severityList($lang->bug->severityList),
    set::userMap($users),
    set::customCols(true),
    set::onRenderCell(jsRaw('window.onRenderBugNameCell')),
    set::checkable($canBatchAction),
    set::checkInfo($browseType == 'resolvedBy' ? jsRaw('function(checks){return window.setStatistics(this, checks);}') : null),
    set::canRowCheckable(jsRaw('function(rowID){return this.getRowInfo(rowID).data.canBeChanged;}')),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&browseType={$currentBrowseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::emptyTip($lang->bug->notice->noBug),
    set::customData($browseType == 'resolvedBy' ? array('pageSummary' => $summary) : array())
);

render();
