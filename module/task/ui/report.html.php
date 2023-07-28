<?php
declare(strict_types=1);
/**
 * The report view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('executionID', $executionID);
jsVar('browseType', $browseType);

toolbar
(
    item(set(
    [
        'text'  => $lang->goback,
        'icon'  => 'back',
        'class' => 'ghost',
        'url'   => createLink('execution', 'task', "executionID=$executionID")
    ])),
    item(set::type('divider')),
    div
    (
        $lang->task->report->common,
        set::class('font-semibold'),
    )
);

$selectCharts = array();
foreach($lang->task->report->charts as $code => $name)
{
    $chart = array();
    $chart['text']  = $name;
    $chart['value'] = $code;

    $selectCharts[] = $chart;
}

sidebar
(
    formPanel
    (
        setID('chartForm'),
        set('title', $lang->task->report->select),
        set('actions', array
        (
            array
            (
                'text'  => $lang->selectAll,
                'class' => 'btn-select-all space',
                'url'   => 'javascript:triggerChecked();'
            ),
            array
            (
                'type'  => 'primary',
                'text'  => $lang->task->report->create,
                'class' => 'btn-select-all space',
                'url'   => 'javascript:createChart();'
            ),
        )),
        checkList
        (
            set::primary(true),
            set::name('charts[]'),
            set::value('code'),
            set::inline(false),
            set::items($selectCharts),
        ),
    ),
);

foreach($lang->report->typeList as $type => $name)
{
    $chartTypeList[] = item
    (
        set::text($name),
        set::url(createLink('task', 'report', "executionID=$executionID&browseType=$browseType&type=$type")),
        set::icon($type == 'default' ? 'list-box' : "chart-{$type}"),
        setClass('btn ghost' . ($type == $chartType ? ' btn-active-line' : ''))
    );
}

$notice = str_replace('%tab%', $lang->task->unclosed . $lang->task->common, $lang->report->notice->help);
$ths    = array('item', 'value', 'percent');

$chartContents = array();
foreach($charts as $chartType => $chartOption)
{
    foreach($datas[$chartType] as $key => $data)
    {
        $trs[] = h::tr
        (
            h::td
            (
                icon('chart-color-dot'),
                setClass('chart-color'),
            ),
            h::td
            (
                $data->name,
                setClass('chart-label'),
                set('align', 'left'),
                set('title', zget($data, 'title', $data->name)),
            ),
            h::td
            (
                $data->value,
                setClass('chart-value'),
                set('align', 'right'),
            ),
            h::td
            (
                ($data->percent * 100) . $lang->percent,
                set('align', 'right'),
            )
        );
    }

    $chartContents[] = div
    (
        setClass('flex items-center'),
        cell
        (
            set::width('70%'),
            div
            (
                setClass('chart-canvas'),
                canvas
                (
                    setID("chart-{$chartType}"),
                    set('width', $chartOption->width),
                    set('height', $chartOption->height),
                    set('data-responsive', true),
                )
            ),
        ),
        cell
        (
            h::table
            (
                setClass('table table-bordered table-condensed table-hover table-chart'),
                set('data-chart', $chartOption->type),
                set('data-target', '#chart-' . $chartType),
                set('data-animation', false),

                h::thead
                (
                    h::tr
                    (
                        h::th
                        (
                            $lang->report->item,
                            setClass('chart-label'),
                            set('colspan', 2),
                        ),
                        h::th
                        (
                            $lang->report->value,
                            set('align', 'right'),
                            set('width', 50),
                        ),
                        h::th
                        (
                            $lang->report->percent,
                            set('align', 'right'),
                            set('width', 60),
                        ),
                    )
                ),
                h::tbody($trs),
            )
        ),
    );
}

panel
(
    setID('chartContainer'),
    toolbar
    (
        set::current($chartType),
        $chartTypeList,
        to::after
        (
            div
            (
                html($notice),
                setClass('text-gray')
            )
        )
    ),
    $chartContents
);

render();
