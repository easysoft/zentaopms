<?php
declare(strict_types=1);
/**
 * The bug view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
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
    set::current($type),
    set::linkParams("mode=bug&type={key}&param=&orderBy={$orderBy}"),
    li(searchToggle(set::module($this->app->rawMethod . 'Bug'), set::open($type == 'bySearch')))
);

$canBatchEdit     = common::hasPriv('bug', 'batchEdit')    && $type == 'assignedTo';
$canBatchConfirm  = common::hasPriv('bug', 'batchConfirm') && $type != 'closedBy';
$canBatchClose    = common::hasPriv('bug', 'batchClose')   && strtolower($type) != 'closedby';
$canBatchAssignTo = common::hasPriv('bug', 'batchAssignTo');
$canBatchAction   = $canBatchEdit || $canBatchConfirm || $canBatchClose || $canBatchAssignTo;

$currentType = $type;
if($type == 'bySearch') $type = $this->session->myBugType;

if($type == 'openedBy')
{
    $config->my->bug->dtable->fieldList['openedBy']['hidden']     = true;
    $config->my->bug->dtable->fieldList['openedDate']['hidden']   = true;
    $config->my->bug->dtable->fieldList['assignedDate']['hidden'] = true;
}

if($type == 'resolvedBy')
{
    $config->my->bug->dtable->fieldList['openedDate']['hidden'] = true;
    $config->my->bug->dtable->fieldList['resolvedBy']['hidden'] = true;
}

if($type == 'assignedBy') $config->my->bug->dtable->fieldList['openedDate']['hidden'] = true;
if($type == 'closedBy')   $config->my->bug->dtable->fieldList['openedDate']['hidden'] = true;
if($type == 'assignedTo') $config->my->bug->dtable->fieldList['assignedTo']['hidden'] = true;
if($app->rawMethod == 'work')
{
    $config->my->bug->dtable->fieldList['status']['hidden']     = true;
    $config->my->bug->dtable->fieldList['openedDate']['hidden'] = true;
}
else
{
    $config->my->bug->dtable->fieldList['deadline']['hidden'] = true;
}

if(!$canBatchAction) $config->bug->dtable->fieldList['id']['type'] = 'id';

$projectBrowseLink = createLink('project', 'browse');
$productLink       = explode('-', $config->productLink);
$productParam      = $config->productLink == 'product-all' ? '' : "productID={product}";
$productBrowseLink = createLink('product', $productLink[1], $productParam);
$config->bug->dtable->fieldList['product']['link'] = 'RAWJS<function(info){ if(info.row.data.shadow) return \'' . $projectBrowseLink . '\'; else return \'' . $productBrowseLink . '\'; }>RAWJS';

foreach($bugs as $bug) $bug->canBeChanged = common::canBeChanged('bug', $bug);

$assignedToItems = array();
foreach ($memberPairs as $key => $value)
{
    if(!$key) continue;
    $key = base64_encode((string)$key); // 编码用户名中的特殊字符
    $assignedToItems[] = array('text' => $value, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => createLink('bug', 'batchAssignTo', "assignedTo=$key&productID=0&type=my"));
}

$footToolbar = $canBatchAction ? array('items' => array
(
    array('text' => $lang->edit, 'className' => 'batch-btn ' . ($canBatchEdit ? '' : 'hidden'), 'data-url' => createLink('bug', 'batchEdit')),
    array('text' => $lang->confirm, 'className' => 'batch-btn ajax-btn ' . ($canBatchConfirm ? '' : 'hidden'), 'data-url' => createLink('bug', 'batchConfirm')),
    array('text' => $lang->close, 'className' => 'batch-btn ajax-btn ' . ($canBatchClose ? '' : 'hidden'), 'data-url' => createLink('bug', 'batchClose')),
    array('text' => $lang->bug->assignedTo, 'className' => ($canBatchAssignTo ? '' : 'hidden'), 'type' => 'dropdown', 'items' => $assignedToItems, 'caret' => 'up', 'data-placement' => 'top-start', 'data-menu' => array('searchBox' => true)),
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary')) : null;

$cols = $config->my->bug->dtable->fieldList;
$bugs = initTableData($bugs, $cols, $this->bug);

dtable
(
    set::cols($cols),
    set::data(array_values($bugs)),
    set::priList($lang->bug->priList),
    set::severityList($lang->bug->severityList),
    set::userMap($users),
    set::fixedLeftWidth('44%'),
    set::onRenderCell(jsRaw('window.onRenderBugNameCell')),
    set::checkable($canBatchAction),
    set::checkInfo($type == 'resolvedBy' ? jsRaw('function(checks){return window.setStatistics(this, checks);}') : null),
    set::canRowCheckable(jsRaw('function(rowID){return this.getRowInfo(rowID).data.canBeChanged;}')),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$currentType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::emptyTip($lang->bug->notice->noBug),
    set::customData($type == 'resolvedBy' ? array('pageSummary' => $summary) : array())
);

render();
