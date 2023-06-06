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

featureBar
(
    set::current($type),
    set::linkParams("project={$project->id}&product={$productID}&branch={$branchID}&orderBy=status,id_desc&build={$buildID}&type={key}&param={$param}"),
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

$closeLink = $type != 'bysearch' ? createLink('project', 'bug', "project={$project->id}&productID={$productID}&branch=$branch&orderBy=$orderBy&build=$buildID&type=$type&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("bugModule")';
sidebar
(
    moduleMenu(set(array
    (
        'modules'   => $moduleTree,
        'activeKey' => $moduleID,
        'closeLink' => $closeLink
    )))
);

$this->bug->buildOperateMenu(null, 'browse');

foreach($bugs as $bug)
{
    $bug->productName = zget($products, $bug->product);
    $bug->storyName   = zget($stories, $bug->story);
    $bug->taskName    = zget($tasks, $bug->task);
    $bug->toTaskName  = zget($tasks, $bug->toTask);
    $bug->module      = zget($modulePairs, $bug->module);
    $bug->branch      = zget($branchTagOption, $bug->branch);
    $bug->project     = zget($projectPairs, $bug->project);
    $bug->execution   = zget($executions, $bug->execution);
    $bug->type        = zget($lang->bug->typeList, $bug->type);
    $bug->confirmed   = zget($lang->bug->confirmedList, $bug->confirmed);
    $bug->resolution  = zget($lang->bug->resolutionList, $bug->resolution);
    $bug->os          = zget($lang->bug->osList, $bug->os);
    $bug->browser     = zget($lang->bug->browserList, $bug->browser);

    $actions = array();
    foreach($this->config->bug->dtable->fieldList['actions']['actionsMap'] as $actionCode => $actionMap)
    {
        $isClickable = $this->bug->isClickable($bug, $actionCode);

        $actions[] = $isClickable ? $actionCode : array('name' => $actionCode, 'disabled' => true);
    }
    $bug->actions = $actions;
}

$cols = array_values($config->bug->dtable->fieldList);
$data = array_values($bugs);

$assignedToItems = array();
foreach ($memberPairs as $key => $value) $assignedToItems[] = array('text' => $value, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('bug', 'batchAssignTo', "assignedTo=$key&projectID={$project->id}&type=project"));

menu
(
    set::id('navAssignedTo'),
    set::class('dropdown-menu'),
    set::items($assignedToItems)
);

$footToolbar = array('items' => array
(
    array('caret' => 'up', 'text' => $lang->bug->assignedTo, 'btnType' => 'secondary', 'url' => '#navAssignedTo','data-toggle' => 'dropdown', 'data-placement' => 'top'),
));

dtable
(
    set::userMap($users),
    set::cols($cols),
    set::data($data),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::footPager
    (
        usePager(),
        set::page($pager->pageID),
        set::recPerPage($pager->recPerPage),
        set::recTotal($pager->recTotal),
        set::linkCreator(helper::createLink('project', 'bug', "projectID={$project->id}&product={$productID}&branch={$branchID}&orderBy=$orderBy&build={$buildID}&type={$type}&param={$param}&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}"))
    ),
);

render();
