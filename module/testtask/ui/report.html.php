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
    )
);

$reports = array();
foreach($lang->testtask->report->charts as $key => $label) $reports[] = array('text' => $label, 'value' => $key);

function getEcharts($tabCharts, $tabDatas)
{
    global $lang;
    $echarts = array();
    foreach($tabCharts as $type => $option)
    {
        $chartData = $tabDatas[$type];
        $echarts[] = tableChart
        (
            set::item('chart-' . $type),
            set::type($option->type),
            set::title($lang->testtask->report->charts[$type]),
            set::datas((array)$chartData),
            set::tableWidth('40%')
        );
    }
    return $echarts;
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
        set::key($type),
        to::prefix(icon($type == 'default' ? 'list-alt' : "chart-{$type}")),
        div(set::className('pb-4 pt-2'), span(set::className('text-gray'), html(str_replace('%tab%', $lang->testtask->wait . $lang->testcase->common, $lang->report->notice->help)))),
        div(getEcharts($charts, $datas))
    );
}

div
(
    set::className('flex items-start'),
    cell
    (
        set::width('240'),
        set::className('bg-white p-4 mr-5'),
        div(set::className('pb-2'), span(set::className('font-bold'), $lang->testtask->report->select)),
        div
        (
            set::className('pb-2'),
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
            set::className('primary ml-4 inited'),
            set('data-on', 'click'),
            set('data-call', 'clickInit'),
            set('data-params', 'event'),
            $lang->testtask->report->create
        )
    ),
    cell
    (
        set::flex('1'),
        set::className('bg-white px-4 py-2'),
        set::id('report'),
        tabs
        (
            on::click('.font-medium', 'changeTab'),
            $tabItems
        )
    )
);

render();
