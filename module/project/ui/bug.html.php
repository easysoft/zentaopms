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
$linkParams = "projectID={$project->id}&productID={$productID}&branch=$branchID&orderBy=status,id_desc&build=$buildID&type={key}&param={$param}&recTotal={$pager->recTotal}&recPerpage={$pager->recPerPage}";
featureBar
(
    set::current($type),
    set::linkParams($linkParams),
    li(searchToggle(set::module('projectBug'), set::open($type == 'bysearch')))
);

$canModify = common::canModify('project', $project);
$canCreate = ($canModify && hasPriv('bug', 'create'));
toolbar
(
    hasPriv('bug', 'export') ? item(set(array
    (
        'text'  => $lang->export,
        'icon'  => 'export',
        'class' => 'ghost',
        'url'   => createLink('bug', 'export', "productID={$productID}&browseType=&projectID={$project->id}"),
        'data-toggle' => 'modal'
    ))) : null,
    $canCreate ? item
    (
        set(array
        (
            'text' => $lang->bug->create,
            'icon' => 'plus',
            'type' => 'primary',
            'url'  => createLink('bug', 'create', "productID={$productID}&branch={$branchID}&extras=projectID={$project->id},moduleID={$moduleID}")
        )),
        setData('app', 'project')
    ) : null
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

$canBatchAssignTo = hasPriv('bug', 'batchAssignTo');

$config->bug->dtable->fieldList['module']['map']    = $modulePairs;
$config->bug->dtable->fieldList['story']['map']     = array('') + $stories;
$config->bug->dtable->fieldList['task']['map']      = array('') + $tasks;
$config->bug->dtable->fieldList['toTask']['map']    = array('') + $tasks;
$config->bug->dtable->fieldList['branch']['map']    = $branchTagOption;
$config->bug->dtable->fieldList['project']['map']   = $projectPairs;
$config->bug->dtable->fieldList['execution']['map'] = $executions;
$config->bug->dtable->fieldList['plan']['map']      = $plans;

foreach($config->bug->dtable->fieldList as $fieldCode => $fieldInfo)
{
    if(!$project->hasProduct && (($project->model != 'scrum' && $fieldCode == 'plan') || $fieldCode == 'branch')) unset($config->bug->dtable->fieldList[$fieldCode]);
}

if(!$canBatchAssignTo) $config->bug->dtable->fieldList['id']['type'] = 'id';

$footToolbar = array();
if($canBatchAssignTo)
{
    $assignedToItems = array();
    foreach ($memberPairs as $key => $value)
    {
        $key = base64_encode((string)$key); // 编码用户名中的特殊字符
        if(!empty($key)) $assignedToItems[] = array('text' => $value, 'innerClass' => 'batch-btn ajax-btn', 'data-url' => createLink('bug', 'batchAssignTo', "assignedTo=$key&projectID={$project->id}&type=project"));
    }

    $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->bug->assignedTo, 'className' => 'btn btn-caret size-sm secondary', 'items' => $assignedToItems, 'type' => 'dropdown', 'data-placement' => 'top');
}

$cols = $this->loadModel('datatable')->getSetting('project');
$bugs = initTableData($bugs, $cols, $this->bug);
if(!$canModify) foreach($bugs as $bug) $bug->actions = array();

dtable
(
    set::cols($cols),
    set::data(array_values($bugs)),
    set::userMap($users),
    set::priList($lang->bug->priList),
    set::severityList($lang->bug->severityList),
    set::customCols(true),
    set::checkable($canBatchAssignTo),
    set::orderBy($orderBy),
    set::sortLink(createLink('project', 'bug', "projectID={$project->id}&productID={$productID}&branchID={$branchID}&orderBy={name}_{sortType}&build={$buildID}&type={$type}&param={$param}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::emptyTip($lang->bug->notice->noBug),
    set::createTip($lang->bug->create),
    set::modules($modulePairs),
    set::createLink($canCreate ? createLink('bug', 'create', "productID={$productID}&branch={$branchID}&extras=projectID={$project->id},moduleID={$moduleID}") : '')
);

render();
