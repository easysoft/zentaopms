<?php
declare(strict_types=1);
/**
 * The report view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('params', "productID={$productID}&taskID={$taskID}&browseType={$browseType}&branchID={$branchID}&moduleID={$moduleID}");

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::level(1),
            set::text($lang->testtask->report->common)
        )
    ),
);

$reports = array();
foreach($lang->testtask->report->charts as $key => $label) $reports[] = array('text' => $label, 'value' => $key);

$colorList = array('#5470C6', '#91CC75', '#FAC858', '#EE6666', '#73C0DE', '#3BA272', '#FC8452', '#9A60B4', '#EA7CCC');
$echarts   = array();
foreach($charts as $type => $option)
{
    $chartData   = $datas[$type];
    $chartOption = array();
    $tableTR     = array();
    $colorList   = array('#5470C6', '#91CC75', '#FAC858', '#EE6666', '#73C0DE', '#3BA272', '#FC8452', '#9A60B4', '#EA7CCC');
    foreach($chartData as $key => $data)
    {
        $color = current($colorList);
        $chartOption[] = array('name' => $data->name, 'value' => $option->type == 'pie' ? $data->value : array('value' => $data->value, 'itemStyle' => array('color' => $color)));
        $tableTR[] = h::tr
        (
            h::td(label(set::class('label-dot mr-2'), set::style(array('background-color' => $color, '--tw-ring-color' => $color))), $data->name),
            h::td($data->value),
            h::td(($data->percent * 100) . '%')
        );
        if(!next($colorList)) reset($colorList);
    }

    $echarts[] = div
    (
        set::class('flex border'),
        cell
        (
            set::width('50%'),
            set::class('border-r chart'),
            div(set::class('center text-base font-bold py-2'), $lang->testtask->report->charts[$type]),
            echarts
            (
                set::color($colorList),
                $option->type != 'pie' ? set::xAxis
                (
                    array
                    (
                        'type' => 'category',
                        'data' => array_column($chartOption, 'name')
                    )
                ) : null,
                $option->type != 'pie' ? set::yAxis(array('type' => 'value')) : null,
                set::series
                (
                    array
                    (
                        array
                        (
                            'data' => $option->type == 'pie' ? $chartOption : array_column($chartOption, 'value'),
                            'type' => $option->type
                        )
                    )
                )
            )->size('100%', 300),
        ),
        cell
        (
            set::width('50%'),
            h::table
            (
                set::class('table'),
                h::tr
                (
                    h::th($lang->report->item),
                    h::th(set::width('100px'), $lang->report->value),
                    h::th(set::width('120px'), $lang->report->percent)
                ),
                $tableTR
            )
        )
    );
}

$tabItems = array();
unset($lang->report->typeList['default']);
foreach($lang->report->typeList as $type => $typeName)
{
    $tabItems[] = tabPane
    (
        set::title($typeName),
        set::active($type == $chartType),
        set::param($type),
        to::prefix(icon($type == 'default' ? 'list-alt' : "chart-{$type}")),
        span(set::class('text-gray'), str_replace('%tab%', $lang->testtask->wait . $lang->testcase->common, $lang->report->notice->help)),
        div($echarts)
    );
}

div
(
    set::class('flex items-start'),
    cell
    (
        set::width('240'),
        set::class('bg-white p-4 mr-5'),
        div(set::class('pb-2'), span(set::class('font-bold'), $lang->testtask->report->select)),
        div
        (
            set::class('pb-2'),
            control
            (
                set::type('checkList'),
                set::name('charts'),
                set::items($reports)
            )
        ),
        btn
        (
            set('data-on', 'click'),
            set('data-call', 'selectAll'),
            $lang->selectAll
        ),
        btn
        (
            set::class('primary ml-4 inited'),
            set('data-on', 'click'),
            set('data-call', 'clickInit'),
            set('data-params', 'event'),
            $lang->testtask->report->create
        )
    ),
    cell
    (
        set::flex('1'),
        set::class('bg-white px-4 py-2'),
        set::id('report'),
        tabs($tabItems)
    )
);

render();
