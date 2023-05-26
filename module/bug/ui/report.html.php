<?php
declare(strict_types=1);
/**
 * The report view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

toolbar
(
    item(set(
    [ 
        'text'  => $lang->goback,
        'icon'  => 'back',
        'class' => 'ghost',
        'url'   => createLink('bug', 'browse', "productID=$productID&branch=0&browseType=$browseType&moduleID=$moduleID")
    ])),
    item(set::type('divider')),
    div
    (
        $lang->bug->report->common,
        set::class('font-semibold'),
    )
);

$selectCharts = array();
foreach($lang->bug->report->charts as $code => $name)
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
        set('title', $lang->bug->report->select),
        set('actions', ''),
        set('actions', array
        (
            item
            (
                set::type('btn'),
                set::text($lang->selectAll),
                setClass('btn-select-all space'),
                on::click(),
            ),
            'submit',
        )),
        set('submitBtnText', $lang->bug->report->create),
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
        set::url('javascript:chageChartType(\"$type\")'),
        set::icon($type == 'default' ? 'list-box' : "chart-{$type}"),
        setClass('btn ghost' . ($type == $chartType ? ' btn-active-line' : ''))
    );
}

$notice = str_replace('%tab%', $lang->bug->unclosed . $lang->bug->common, $lang->report->notice->help);
$ths = array('item', 'value', 'percent');

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
    toolbar
    (
        set::current($chartType),
        $chartTypeList,
        to::after
        (
            div
            (
                $notice,
                setClass('text-gray')
            )
        )
    ),
    $chartContents
);

render();
