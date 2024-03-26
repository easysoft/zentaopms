<?php
declare(strict_types=1);
/**
 * The testtask view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
include './featurebar.html.php';

jsVar('trunkLang', $lang->trunk);

$that = zget($lang->user->thirdPerson, $user->gender);
$testtaskNavs['assignedTo'] = array('text' => sprintf($lang->user->testTask2Him, $that), 'url' => inlink('testtask', "userID={$user->id}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table', 'active' => true);

$cols = array();
foreach($config->user->defaultFields['testtask'] as $field) $cols[$field] = $config->testtask->dtable->fieldList[$field];

$statusKey            = 'status';
$titleKey             = 'title';
$statusValue          = $cols[$statusKey];
$statusValue['title'] = $lang->testtask->statusAB;
unset($cols[$statusKey]);

$titleIndex = array_search($titleKey, array_keys($cols)) + 1;
$cols       = array_merge(array_slice($cols, 0, $titleIndex), array($statusKey => $statusValue), array_slice($cols, $titleIndex));

$cols['id']['checkbox']    = false;
$cols['title']['data-app'] = 'qa';

$cols = array_map(function($col)
{
    unset($col['fixed'], $col['group']);
    return $col;
}, $cols);

$waitCount    = 0;
$testingCount = 0;
$blockedCount = 0;
$doneCount    = 0;
foreach($tasks as $task)
{
    if($task->status == 'wait')    $waitCount ++;
    if($task->status == 'doing')   $testingCount ++;
    if($task->status == 'blocked') $blockedCount ++;
    if($task->status == 'done')    $doneCount ++;
}
$summary = sprintf($lang->testtask->allSummary, count($tasks), $waitCount, $testingCount, $blockedCount, $doneCount);

div
(
    setClass('shadow-sm rounded canvas'),
    nav
    (
        setClass('dtable-sub-nav py-1'),
        set::items($testtaskNavs)
    ),
    dtable
    (
        set::_className('shadown-none'),
        set::extraHeight('+.dtable-sub-nav'),
        set::bordered(true),
        set::cols($cols),
        set::data(array_values($tasks)),
        set::orderBy($orderBy),
        set::sortLink(inlink('testtask', "userID={$user->id}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
        set::footPager(usePager()),
        set::onRenderCell(jsRaw('window.renderCell')),
        set::footer(array(array('html' => $summary, 'className' => "text-dark"), 'flex', 'pager'))
    )
);

render();
