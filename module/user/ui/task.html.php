<?php
declare(strict_types=1);
/**
 * The bug view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
include './featurebar.html.php';

jsVar('todayLabel', $lang->today);
jsVar('yesterdayLabel', $lang->yesterday);
jsVar('childrenAB', $lang->task->childrenAB);
jsVar('multipleAB', $lang->task->multipleAB);

$that = zget($lang->user->thirdPerson, $user->gender);
$taskNavs['assignedTo'] = array('text' => sprintf($lang->user->assignedTo, $that), 'url' => inlink('task', "userID={$user->id}&type=assignedTo&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$taskNavs['openedBy']   = array('text' => sprintf($lang->user->openedBy,   $that), 'url' => inlink('task', "userID={$user->id}&type=openedBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$taskNavs['finishedBy'] = array('text' => sprintf($lang->user->finishedBy, $that), 'url' => inlink('task', "userID={$user->id}&type=finishedBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$taskNavs['closedBy']   = array('text' => sprintf($lang->user->closedBy,   $that), 'url' => inlink('task', "userID={$user->id}&type=closedBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$taskNavs['canceledBy'] = array('text' => sprintf($lang->user->canceledBy, $that), 'url' => inlink('task', "userID={$user->id}&type=canceledBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
if(isset($taskNavs[$type])) $taskNavs[$type]['active'] = true;

$this->loadModel('my');
$cols = array();
foreach($config->user->defaultFields['task'] as $field) $cols[$field] = $config->my->task->dtable->fieldList[$field];
$cols['id']['checkbox']       = false;
$cols['name']['nestedToggle'] = false;
$cols['name']['data-toggle']  = 'modal';
$cols['name']['data-size']    = 'lg';

$cols = array_map(function($col)
{
    unset($col['fixed'], $col['group']);
    return $col;
}, $cols);

$tasks = initTableData($tasks, $cols, $this->task);
foreach($tasks as $task)
{
    $task->estimateLabel = $task->estimate . $lang->execution->workHourUnit;
    $task->consumedLabel = $task->consumed . $lang->execution->workHourUnit;
    $task->leftLabel     = $task->left     . $lang->execution->workHourUnit;
}

div
(
    setClass('shadow-sm rounded canvas'),
    nav
    (
        setClass('dtable-sub-nav py-1'),
        set::items($taskNavs)
    ),
    dtable
    (
        set::_className('shadow-none'),
        set::extraHeight('+.dtable-sub-nav'),
        set::userMap($deptUsers),
        set::bordered(true),
        set::cols($cols),
        set::data(array_values($tasks)),
        set::orderBy($orderBy),
        set::sortLink(inlink('task', "userID={$user->id}&type={$type}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
        set::onRenderCell(jsRaw('window.renderCell')),
        set::footPager(usePager())
    )
);

render();
