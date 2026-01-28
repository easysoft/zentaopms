<?php
declare(strict_types=1);
/**
 * The bug view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

/* zin: Define the set::module('bug') feature bar on main menu. */
featureBar
(
    set::current($type),
    set::linkParams("executionID={$execution->id}&productID={$productID}&branch={$branchID}&orderBy={$orderBy}&build={$buildID}&type={key}&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    li(searchToggle(set::module('executionBug'), set::open($type == 'bysearch')))
);

/* zin: Define the toolbar on main menu. */
$canExportBug = hasPriv('bug', 'export');
$canCreateBug = hasPriv('bug', 'create') && common::canModify('execution', $execution);

if($canExportBug) $exportItem = array
(
    'icon' => 'export',
    'class' => 'ghost',
    'text' => $lang->export,
    'data-toggle' => 'modal',
    'url' => $this->createLink('bug', 'export', "productID={$productID}&browseType=&executionID={$execution->id}")
);
if($canCreateBug) $createItem = array
(
    'icon' => 'plus',
    'class' => 'primary createBug-btn',
    'text' => $lang->bug->create,
    'data-app' => 'execution',
    'url' => $this->createLink('bug', 'create', "productID={$defaultProduct}&branch=0&extras=executionID={$execution->id},moduleID={$moduleID}")
);

toolbar
(
    !empty($canExportBug) ? item(set($exportItem)) : null,
    !empty($createItem) ? item(set($createItem)) : null
);

/* zin: Define the sidebar in main content. */
sidebar
(
    moduleMenu(set(array(
        'modules'   => $moduleTree,
        'activeKey' => $moduleID,
        'closeLink' => $this->createLink('execution', 'bug', "executionID={$execution->id}")
    )))
);

$bugs = initTableData($bugs, $this->config->bug->dtable->fieldList, $this->bug);
$canBatchAssignTo = common::hasPriv('bug', 'batchAssignTo');

if($canBatchAssignTo)
{
    $pinyinItems     = common::convert2Pinyin($users);
    $assignedToItems = array();
    foreach($users as $account => $name)
    {
        if(empty($account)) continue;
        $account = base64_encode((string)$account); // 编码用户名中的特殊字符
        if($account != 'closed') $assignedToItems[] = array('text' => $name, 'keys' => zget($pinyinItems, $name, ''), 'innerClass' => 'batch-btn ajax-btn', 'data-url' => createLink('bug', 'batchAssignTo', "assignedTo={$account}&objectID={$execution->id}"));
    }

    $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->bug->assignedTo, 'className' => 'btn btn-caret size-sm secondary', 'items' => $assignedToItems, 'type' => 'dropdown', 'data-placement' => 'top', 'data-menu' => array('searchBox' => true));
}

jsVar('checkedSummary', $lang->selectedItems);
jsVar('caseCommonLang', $this->lang->testcase->common);

$cols = $this->loadModel('datatable')->getSetting('execution');
if(isset($cols['module']))      $cols['module']['map']      = $modulePairs;
if(isset($cols['branch']))      $cols['branch']['map']      = $branchOption;
if(isset($cols['project']))     $cols['project']['map']     = $projectPairs;
if(isset($cols['openedBuild'])) $cols['openedBuild']['map'] = $builds;
if(isset($cols['plan']))        $cols['plan']['map']        = array('') + $plans;
if(isset($cols['task']))        $cols['task']['map']        = array('') + $tasks;
if(isset($cols['toTask']))      $cols['toTask']['map']      = array('') + $tasks;
if(isset($cols['story']))       $cols['story']['map']       = array('') + $stories;

if(isset($cols['activatedCount'])) $cols['activatedCount']['map'] = array('');

$bugs = initTableData($bugs, $cols, $this->execution);

dtable
(
    set::userMap($users),
    set::cols($cols),
    set::data(array_values($bugs)),
    set::priList($lang->bug->priList),
    set::severityList($lang->bug->severityList),
    set::checkable($canBatchAssignTo),
    set::orderBy($orderBy),
    set::sortLink(createLink('execution', 'bug', "executionID={$execution->id}&productID={$productID}&branch={$branchID}&orderBy={name}_{sortType}&build=$buildID&type=$type&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::customCols(true),
    set::footPager(
        usePager(array('linkCreator' => helper::createLink('execution', 'bug', "executionID={$execution->id}&productID={$productID}&branch={$branchID}&orderBy={$orderBy}&build=$buildID&type=$type&param=$param&recTotal={$pager->recTotal}&recPerPage={recPerPage}&pageID={page}")))
    ),
    set::emptyTip($lang->bug->notice->noBug),
    set::createTip($lang->bug->create),
    set::createLink($canCreateBug ? createLink('bug', 'create', "productID={$defaultProduct}&branch=0&extras=executionID={$execution->id}") : ''),
    set::checkInfo(jsRaw('function(checkedIDList){return window.setStatistics(this, checkedIDList);}')),
    set::summary($summary),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::modules($modulePairs)
);
render();
