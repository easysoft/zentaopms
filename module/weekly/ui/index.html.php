<?php
declare(strict_types=1);
/**
 * The index view file of weekly module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <wangyidong@chandao.com>
 * @package     weekly
 * @link        https://www.zentao.net
 */
namespace zin;

if(hasPriv('weekly', 'exportweeklyreport'))
{
    mainNavbar
    (
        to('right', btn
            (
                setID('exportWeeklyBtn'),
                setClass('pull-right mt-1 secondary'),
                setData('toggle', 'modal'),
                setData('selectedweekbegin', $date),
                set::url($this->createLink('weekly', 'exportweeklyreport', "module=weekly&projectID={$project->id}")),
                $lang->export
            )
        )
    );
}

/* Build overview. */
h::table
(
    setClass('table bordered bg-white'),
    h::tr
    (
        h::th(setClass('w-1/4'), $lang->weekly->term),
        h::td(setClass('w-1/4'), $monday . ' ~ ' . $lastDay),
        h::th(setClass('w-1/4'), $lang->weekly->master),
        h::td(setClass('w-1/4'), zget($users, $project->PM, ''))
    ),
    h::tr
    (
        h::th($lang->weekly->project),
        h::td(setClass('projectName'), set::title($project->name), $project->name),
        h::th($lang->weekly->staff),
        h::td($staff)
    )
);

/* Build summary. */
$projectCost = zget($this->config->custom, 'cost', 1);
div(setClass('page-title'), h::h4(setClass('m-2.5 text-center'), $lang->weekly->summary));
h::table
(
    setClass('table bordered bg-white text-center'),
    h::tr
    (
        h::td
        (
            $lang->weekly->progress,
            span(setClass('pl-1'), setData(array('toggle' => 'dropdown', 'trigger' => 'hover')), icon('help')),
            h::menu(setClass("dropdown-menu custom p-1 h-44 overflow-auto text-left"), setID('helpDropdown'), setStyle(array('font-weight' => 'normal', 'font-size' => '12px', 'line-height' => '1.5')), html($lang->weekly->reportHelpNotice))
        ),
        h::td(),
        h::td($lang->weekly->analysisResult),
        h::td()
    ),
    h::tr
    (
        h::td($lang->weekly->pv),
        h::td($pv),
        h::td(set::rowspan(4), $lang->weekly->progress),
        h::td(set::rowspan(4), html($this->weekly->getTips('progress', $sv) . '<br/>' . $this->weekly->getTips('cost', $cv)))
    ),
    h::tr(h::td($lang->weekly->ev), h::td($ev)),
    h::tr(h::td($lang->weekly->ac), h::td($ac)),
    h::tr(h::td($lang->weekly->sv), h::td($sv ? $sv . '%' : '')),
    h::tr
    (
        h::td($lang->weekly->cv),
        h::td($cv ? $cv . '%' : ''),
        h::td($lang->weekly->cost),
        h::td(setClass('projectCost'), empty($projectCost) ? 0 : $ac * $projectCost)
    ),
);

/* Build table. */
$buildTable = function($title, $cols, $data, $total = array(), $tableClass = '')
{
    $thead = h::thead
    (
        h::tr(array_map(function($col) { return h::th(setClass(zget($col, 'class', '')), $col['name']); }, $cols))
    );

    $trList = array();
    foreach($data as $row)
    {
        $trList[] = h::tr(array_map(function($col) { return h::td(setClass(zget($col, 'class', '')), $col['value']); }, $row));
    }
    if($total) $trList[] = h::tr(h::td(set::colspan(count($cols)), setClass(zget($total, 'class', '')), $total['content']));
    $tbody = h::tbody($trList);

    return div
    (
        div(setClass('page-title'), h::h4(setClass('m-2.5 text-center'), $title)),
        h::table(setClass("table bg-white {$tableClass}"), $thead, $tbody)
    );
};

/* Build finished table. */
$cols = array();
$cols[] = array('name' => $lang->idAB,              'class' => 'w-24');
$cols[] = array('name' => $lang->task->name,        'class' => 'text-left');
$cols[] = array('name' => $lang->task->estStarted,  'class' => 'text-left w-32');
$cols[] = array('name' => $lang->task->deadline,    'class' => 'text-left w-32');
$cols[] = array('name' => $lang->task->realStarted, 'class' => 'text-left w-32');
$cols[] = array('name' => $lang->task->finishedBy,  'class' => 'text-left w-32');

$data = array();
foreach($finished as $task)
{
    $data[] = array
    (
        array('class' => 'text-center', 'value' => sprintf('%03d', $task->id)),
        array('class' => 'text-left',   'value' => h::a(set::href($this->createLink('task', 'view', "id={$task->id}")), set::title($task->name), $task->name)),
        array('class' => 'text-left',   'value' => $task->estStarted),
        array('class' => 'text-left',   'value' => $task->deadline),
        array('class' => 'text-left',   'value' => !helper::isZeroDate($task->realStarted) ? substr($task->realStarted, 0, 11) : ''),
        array('class' => 'text-left',   'value' => zget($users, $task->finishedBy, ''))
    );
}
$buildTable($lang->weekly->finished, $cols, $data, array('content' => sprintf($lang->weekly->totalCount, count($finished)), 'class' => 'text-right font-bold'));

/* Build postponed table. */
$cols = array();
$cols[] = array('name' => $lang->idAB,              'class' => 'w-24');
$cols[] = array('name' => $lang->task->name,        'class' => 'text-left');
$cols[] = array('name' => $lang->task->assignedTo,  'class' => 'text-left w-24');
$cols[] = array('name' => $lang->task->estStarted,  'class' => 'text-left w-32');
$cols[] = array('name' => $lang->task->deadline,    'class' => 'text-left w-32');
$cols[] = array('name' => $lang->task->realStarted, 'class' => 'text-left w-32');
$cols[] = array('name' => $lang->task->progress,    'class' => 'text-left w-24');

$data = array();
foreach($postponed as $task)
{
    $data[] = array
    (
        array('class' => 'text-center', 'value' => sprintf('%03d', $task->id)),
        array('class' => 'text-left',   'value' => h::a(set::href($this->createLink('task', 'view', "id={$task->id}")), set::title($task->name), $task->name)),
        array('class' => 'text-left',   'value' => zget($users, $task->assignedTo)),
        array('class' => 'text-left',   'value' => $task->estStarted),
        array('class' => 'text-left',   'value' => $task->deadline),
        array('class' => 'text-left',   'value' => !helper::isZeroDate($task->realStarted) ? substr($task->realStarted, 0, 11) : ''),
        array('class' => 'text-left',   'value' => $task->progress)
    );
}
$buildTable($lang->weekly->postponed, $cols, $data, array('content' => sprintf($lang->weekly->totalCount, count($postponed)), 'class' => 'text-right font-bold'));

/* Build next week data table. */
$cols = array();
$cols[] = array('name' => $lang->idAB,              'class' => 'w-24');
$cols[] = array('name' => $lang->task->name,        'class' => 'text-left');
$cols[] = array('name' => $lang->task->assignedTo,  'class' => 'text-left w-24');
$cols[] = array('name' => $lang->task->estStarted,  'class' => 'text-left w-32');
$cols[] = array('name' => $lang->task->deadline,    'class' => 'text-left w-32');

$data = array();
foreach($nextWeek as $task)
{
    $data[] = array
    (
        array('class' => 'text-center', 'value' => sprintf('%03d', $task->id)),
        array('class' => 'text-left',   'value' => h::a(set::href($this->createLink('task', 'view', "id={$task->id}")), set::title($task->name), $task->name)),
        array('class' => 'text-left',   'value' => zget($users, $task->assignedTo)),
        array('class' => 'text-left',   'value' => $task->estStarted),
        array('class' => 'text-left',   'value' => $task->deadline)
    );
}
$buildTable($lang->weekly->nextWeek, $cols, $data, array('content' => sprintf($lang->weekly->totalCount, count($nextWeek)), 'class' => 'text-right font-bold'));

/* Build workload by type table. */
$cols = array();
$cols[] = array('name' => $lang->task->type);
foreach(array_filter($lang->task->typeList) as $type) $cols[] = array('name' => $type);
$cols[] = array('name' => $lang->weekly->total);

$data      = array();
$total     = 0;
$data[0][] = array('value' => $lang->weekly->workload);
foreach(array_keys(array_filter($lang->task->typeList)) as $type)
{
    $worktimes = zget($workload, $type, 0);
    $total    += $worktimes;
    $data[0][] = array('value' => $worktimes);
}
$data[0][] = array('value' => $total);
$buildTable($lang->weekly->workloadByType, $cols, $data, array(), 'mb-5 text-center');