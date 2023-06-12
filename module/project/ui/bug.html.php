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

$config->bug->dtable->fieldList['module']['map']    = $modulePairs;
$config->bug->dtable->fieldList['product']['map']   = $products;
$config->bug->dtable->fieldList['story']['map']     = $stories;
$config->bug->dtable->fieldList['task']['map']      = $tasks;
$config->bug->dtable->fieldList['toTask']['map']    = $tasks;
$config->bug->dtable->fieldList['branch']['map']    = $branchTagOption;
$config->bug->dtable->fieldList['project']['map']   = $projectPairs;
$config->bug->dtable->fieldList['execution']['map'] = $executions;

$bugs = initTableData($bugs, $config->bug->dtable->fieldList, $this->bug);
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
    set::cols($config->bug->dtable->fieldList),
    set::data($data),
    set::userMap($users),
    set::customCols(true),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
);

render();
