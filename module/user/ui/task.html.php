<?php
declare(strict_types=1);
/**
 * The bug view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
include './featurebar.html.php';

jsVar('todayLabel', $lang->today);
jsVar('yesterdayLabel', $lang->yesterday);
jsVar('parentAB', $lang->task->parentAB);
jsVar('childrenAB', $lang->task->childrenAB);
jsVar('multipleAB', $lang->task->multipleAB);
jsVar('delayWarning', $lang->task->delayWarning);

$that = zget($lang->user->thirdPerson, $user->gender);
$taskNavs['assignedTo'] = array('text' => sprintf($lang->user->assignedTo, $that), 'url' => inlink('task', "userID={$user->id}&browseType=assignedTo&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$taskNavs['openedBy']   = array('text' => sprintf($lang->user->openedBy,   $that), 'url' => inlink('task', "userID={$user->id}&browseType=openedBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$taskNavs['finishedBy'] = array('text' => sprintf($lang->user->finishedBy, $that), 'url' => inlink('task', "userID={$user->id}&browseType=finishedBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$taskNavs['myInvolved'] = array('text' => sprintf($lang->user->involved,   $that), 'url' => inlink('task', "userID={$user->id}&browseType=myInvolved&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$taskNavs['closedBy']   = array('text' => sprintf($lang->user->closedBy,   $that), 'url' => inlink('task', "userID={$user->id}&browseType=closedBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$taskNavs['canceledBy'] = array('text' => sprintf($lang->user->canceledBy, $that), 'url' => inlink('task', "userID={$user->id}&browseType=canceledBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
if(isset($taskNavs[$browseType])) $taskNavs[$browseType]['active'] = true;

if(isset($config->user->task->dtable->fieldList['relatedObject']))
{
    $config->user->task->dtable->fieldList['relatedObject']['link'] = hasPriv('custom', 'showRelationGraph') ? "RAWJS<function(info){ if(info.row.data.relatedObject == 0) return 0; else return '" . helper::createLink('custom', 'showRelationGraph', 'objectID={id}&objectType=task') . "'; }>RAWJS" : null;
}
$cols = $this->loadModel('datatable')->getSetting('user', 'task');

if($config->edition != 'open' && !empty($tasks))
{
    $relatedObjectList = $this->loadModel('custom')->getRelatedObjectList(array_keys($tasks), 'task', 'byRelation', true);
    foreach($tasks as $task) $task->relatedObject = zget($relatedObjectList, $task->id, 0);
}

$tasks = initTableData($tasks, $config->user->task->dtable->fieldList, $this->task);
foreach($tasks as $task)
{
    $task->rawStatus     = $task->status;
    $task->estimateLabel = $task->estimate . $lang->execution->workHourUnit;
    $task->consumedLabel = $task->consumed . $lang->execution->workHourUnit;
    $task->leftLabel     = $task->left     . $lang->execution->workHourUnit;
}

div
(
    setClass('shadow-sm rounded canvas'),
    nav(setClass('dtable-sub-nav py-1'), set::items($taskNavs)),
    dtable
    (
        set::_className('shadow-none'),
        set::extraHeight('+.dtable-sub-nav'),
        set::userMap($deptUsers),
        set::bordered(true),
        set::cols($cols),
        set::data(array_values($tasks)),
        set::customCols(true),
        set::priList($lang->task->priList),
        set::orderBy($orderBy),
        set::sortLink(inlink('task', "userID={$user->id}&browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
        set::onRenderCell(jsRaw('window.renderCell')),
        set::footPager(usePager())
    )
);

render();
