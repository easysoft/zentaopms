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

$testcaseTitle = "[" . $lang->testcase->common . "#{case}]";
$testcaseLink = createLink('testcase', 'view', "caseID={case}&version={caseVersion}");

jsVar('testcaseTitle', $testcaseTitle);
jsVar('testcaseLink', $testcaseLink);

featurebar
(
    set::current($type),
    set::linkParam("type=$type"),
    li(searchToggle()),
);

$canBatchEdit     = common::hasPriv('bug', 'batchEdit')    && $type == 'assignedTo';
$canBatchConfirm  = common::hasPriv('bug', 'batchConfirm') && $type != 'closedBy';
$canBatchClose    = common::hasPriv('bug', 'batchClose')   && strtolower($type) != 'closedby';
$canBatchAssignTo = common::hasPriv('bug', 'batchAssignTo');
$canBatchAction   = $canBatchEdit || $canBatchConfirm || $canBatchClose || $canBatchAssignTo;

if($type == 'openedBy')       unset($config->my->bug->dtable->fieldList['openedBy']);
if($type == 'assignedTo')     unset($config->my->bug->dtable->fieldList['assignedTo']);
if($type == 'resolvedBy')     unset($config->my->bug->dtable->fieldList['resolvedBy']);
if($app->rawMethod != 'work') unset($config->my->bug->dtable->fieldList['deadline']);
if(!$canBatchAction) $config->my->bug->dtable->fieldList['id']['type'] = 'id';

$projectBrowseLink = createLink('project', 'browse');
$productLink       = explode('-', $config->productLink);
$param             = $config->productLink == 'product-all' ? '' : "productID={product}";
$productBrowseLink = createLink('product', $productLink[1], $param);
$config->my->bug->dtable->fieldList['product']['link'] = 'RAWJS<function(info){ if(info.row.data.shadow) return \'' . $projectBrowseLink . '\'; else return \'' . $productBrowseLink . '\'; }>RAWJS';

foreach($bugs as $bug) $bug->canBeChanged = common::canBeChanged('bug', $bug);

$bugs = initTableData($bugs, $config->my->bug->dtable->fieldList, $this->bug);
$bugs = array_values($bugs);

$footToolbar = array('items' => array
(
    array('text' => $lang->edit, 'className' => 'batch-btn ' . ($canBatchEdit ? '' : 'hidden'), 'data-url' => helper::createLink('bug', 'batchEdit')),
    array('text' => $lang->confirm, 'className' => 'batch-btn ajax-btn ' . ($canBatchConfirm ? '' : 'hidden'), 'data-url' => helper::createLink('bug', 'batchConfirm')),
    array('text' => $lang->close, 'className' => 'batch-btn ajax-btn ' . ($canBatchClose ? '' : 'hidden'), 'data-url' => helper::createLink('bug', 'batchClose')),
    array('text' => $lang->bug->assignedTo, 'className' => ($canBatchAssignTo ? '' : 'hidden'), 'caret' => 'up', 'url' => '#navAssignedTo','data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

$assignedToItems = array();
foreach ($memberPairs as $key => $value)
{
    $assignedToItems[] = array('text' => $value, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('bug', 'batchAssignTo', "assignedTo=$key&productID={$product->id}&type=product"));
}

menu
(
    set::id('navAssignedTo'),
    set::class('dropdown-menu'),
    set::items($assignedToItems)
);

dtable
(
    set::cols($config->my->bug->dtable->fieldList),
    set::data($bugs),
    set::userMap($users),
    set::onRenderCell(jsRaw('window.onRenderBugNameCell')),
    set::checkable($canBatchAction ? true : false),
    set::canRowCheckable(jsRaw('function(rowID){return this.getRowInfo(rowID).data.canBeChanged;}')),
    $canBatchAction ? set::footToolbar($footToolbar) : null,
    set::footPager(usePager()),
);

render();
