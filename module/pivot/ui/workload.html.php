<?php
declare(strict_types = 1);
/**
 * The workload view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('weekend', $config->execution->weekend);

$cols = $config->pivot->dtable->workload->fieldList;
$cols['user']['map'] = $users;

$generateData = function() use ($lang, $title, $cols, $workload, $depts, $dept, $begin, $end, $days, $workhour, $assign)
{
    return array
    (
        div
        (
            setID('conditions'),
            setClass('flex gap-2 bg-canvas p-2'),
            on::change('loadWorkload'),
            inputGroup
            (
                setClass('w-1/6'),
                $lang->pivot->dept,
                picker
                (
                    setClass('w-full'),
                    set(array('name' => 'dept', 'items' => $depts, 'value' => $dept, 'required' => true))
                )
            ),
            inputGroup
            (
                setClass('w-1/2'),
                $lang->pivot->beginAndEnd,
                datePicker(set(array('name' => 'begin', 'value' => $begin))),
                $lang->pivot->to,
                datePicker(set(array('name' => 'end', 'value' => $end))),
                $lang->pivot->diffDays,
                input(set(array('name' => 'days', 'value' => $days, 'class' => 'text-right readonly w-1/6')))
            ),
            div
            (
                setClass('flex gap-2 w-1/3'),
                inputGroup
                (
                    setClass('w-5/12'),
                    $lang->pivot->workhour,
                    input(set(array('name' => 'workhour', 'value' => $workhour, 'class' => 'text-right')))
                ),
                picker
                (
                    setClass('w-1/3'),
                    set(array('name' => 'assign', 'items' => $lang->pivot->assign, 'value' => $assign, 'required' => true))
                ),
                button(setClass('btn primary w-1/4'), on::click('loadWorkload'), $lang->pivot->query)
            )
        ),
        panel
        (
            setID('pivotPanel'),
            set::title($title),
            set::shadow(false),
            set::bodyClass('pt-0'),
            to::titleSuffix
            (
                icon
                (
                    setClass('cursor-pointer'),
                    setData(array('toggle' => 'tooltip', 'title' => $lang->pivot->workloadDesc, 'placement' => 'right', 'className' => 'text-gray border border-light', 'type' => 'white')),
                    'help'
                )
            ),
            dtable
            (
                set::striped(true),
                set::bordered(true),
                set::cols($cols),
                set::data($workload),
                set::emptyTip($lang->error->noData),
                set::plugins(array('cellspan')),
                set::getCellSpan(jsRaw('getCellSpan')),
                set::cellSpanOptions(array(
                    'user'        => array('rowspan' => 'userRowspan'),
                    'totalTasks'  => array('rowspan' => 'userRowspan'),
                    'totalHours'  => array('rowspan' => 'userRowspan'),
                    'workload'    => array('rowspan' => 'userRowspan'),
                    'projectName' => array('rowspan' => 'projectRowspan')
                ))
            )
        )
    );
};
