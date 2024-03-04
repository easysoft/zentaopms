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

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::level(1),
            set::text($lang->task->report->common)
        )
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

function getEcharts($tabCharts, $tabDatas)
{
    global $lang;
    $echarts = array();
    foreach($tabCharts as $type => $option)
    {
        $chartData = $tabDatas[$type];
        $echarts[] = tableChart
            (
                set::type($option->type),
                set::title($lang->task->report->charts[$type]),
                set::datas((array)$chartData),
                set::tableWidth('40%'),
                set::tableHeaders(array(
                    'item'    => $lang->task->report->{$type}->item,
                    'value'   => $lang->task->report->value,
                    'percent' => $lang->report->percent
                ))
            );
    }
    return $echarts;
}

$chartContents = array();
foreach($lang->report->typeList as $type => $typeName)
{
    $link = createLink('task', 'report', "executionID=$executionID&browseType=$browseType&type=%s");
    $chartContents[] = tabPane
    (
        set::key($type),
        set::title($typeName),
        set::active($type == $chartType),
        set::param(sprintf($link, $type)),
        to::prefix(icon($type == 'default' ? 'list-alt' : "chart-{$type}")),
        div
        (
            setClass('pb-4'),
            span(setClass('text-gray'),
            html(str_replace('%tab%', $lang->task->waitTask . $lang->testcase->common, $lang->report->notice->help)))
        ),
        div(getEcharts($charts, $datas))
    );
}

div
(
    setClass('flex flex-nowrap'),
    cell
    (
        set::width('240'),
        formPanel
        (
            setClass('chart-form'),
            setID('chartForm'),
            set::title($lang->task->report->select),
            set::actionsClass('justify-start'),
            set::actions
            (
                array
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
                    )
                )
            ),
            checkList
            (
                set::primary(true),
                set::name('charts[]'),
                set::value('code'),
                set::inline(false),
                set::items($selectCharts)
            )
        )
    ),
    cell
    (
        setID('chartContainer'),
        set::flex('1'),
        setClass('ml-5 bg-white px-4 py-2'),
        tabs
        (
            on::click('.font-medium', 'changeTab'),
            $chartContents
        )
    )
);
