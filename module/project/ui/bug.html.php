<?php
declare(strict_types=1);
/**
 * The bug view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

$linkParams = "projectID={$project->id}&productID={$productID}&branch=$branchID&orderBy=status,id_desc&build=$buildID&type={key}" . ($type == 'bysearch' ? '' : "&param=$param");
featureBar
(
    set::current($type),
    set::linkParams($linkParams),
    li(searchToggle())
);

toolbar
(
    hasPriv('bug', 'export') ? item(set(array
    (
        'text'  => $lang->bug->export,
        'icon'  => 'export',
        'class' => 'ghost',
        'url'   => createLink('bug', 'export', "productID={$productID}&orderBy=$orderBy&browseType=&projectID={$project->id}"),
        'data-toggle' => 'modal'
    ))) : null,
    (common::canModify('project', $project) && hasPriv('bug', 'create')) ? item(set(array
    (
        'text' => $lang->bug->create,
        'icon' => 'plus',
        'class' => 'primary',
        'url' => createLink('bug', 'create', "productID={$productID}&branch=$branchID&extras=projectID=$project->id")
    ))) : null,
);

$closeLink = $type != 'bysearch' ? createLink('project', 'bug', "projectID={$project->id}&productID={$productID}&branchID=$branchID&orderBy=$orderBy&build=$buildID&type=$type&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("bugModule")';
sidebar
(
    moduleMenu(set(array
    (
        'modules'   => $moduleTree,
        'activeKey' => $moduleID,
        'closeLink' => $closeLink
    )))
);

$canBatchAssignTo = common::hasPriv('bug', 'batchAssignTo');

$config->bug->dtable->fieldList['module']['map']    = $modulePairs;
$config->bug->dtable->fieldList['story']['map']     = $stories;
$config->bug->dtable->fieldList['task']['map']      = $tasks;
$config->bug->dtable->fieldList['toTask']['map']    = $tasks;
$config->bug->dtable->fieldList['branch']['map']    = $branchTagOption;
$config->bug->dtable->fieldList['project']['map']   = $projectPairs;
$config->bug->dtable->fieldList['execution']['map'] = $executions;

foreach($config->bug->dtable->fieldList as $fieldCode => $fieldInfo)
{
    if(!$project->hasProduct && (($project->model != 'scrum' && $fieldCode == 'plan') || $fieldCode == 'branch')) unset($config->bug->dtable->fieldList[$fieldCode]);
}

if(!$canBatchAssignTo) $config->bug->dtable->fieldList['id']['type'] = 'id';

foreach($bugs as $bug) $bug->canBeChanged = common::canBeChanged('bug', $bug);

$assignedToItems = array();
foreach ($memberPairs as $key => $value) $assignedToItems[] = array('text' => $value, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('bug', 'batchAssignTo', "assignedTo=$key&projectID={$project->id}&type=project"));

menu
(
    set::id('navAssignedTo'),
    set::className('dropdown-menu'),
    set::items($assignedToItems)
);

$footToolbar = $canBatchAssignTo ? array('items' => array
(
    array('caret' => 'up', 'text' => $lang->bug->assignedTo, 'btnType' => 'secondary', 'url' => '#navAssignedTo','data-toggle' => 'dropdown', 'data-placement' => 'top'),
)) : null;

$cols = $this->loadModel('datatable')->getSetting('project');
$bugs = initTableData($bugs, $cols, $this->bug);

dtable
(
    set::cols($cols),
    set::data(array_values($bugs)),
    set::userMap($users),
    set::customCols(true),
    set::footToolbar($footToolbar),
    set::checkable($canBatchAssignTo),
    set::canRowCheckable(jsRaw('function(rowID){return this.getRowInfo(rowID).data.canBeChanged;}')),
    set::orderBy($orderBy),
    set::sortLink(createLink('project', 'bug', "projectID={$project->id}&productID={$productID}&branchID={$branchID}&orderBy={name}_{sortType}&build={$buildID}&type={$type}&param={$param}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
);

render();
