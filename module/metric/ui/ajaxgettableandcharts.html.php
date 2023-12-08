<?php
declare(strict_types=1);
/**
 * The browse view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('updateTimeTip', $lang->metric->updateTimeTip);

div
(
    setClass("table-and-chart table-and-chart-{$viewType}"),
    $groupData ? div
    (
        setClass('table-side'),
        setStyle(array('flex-basis' => $tableWidth . 'px')),
        div
        (
            dtable
            (
                $viewType == 'multiple' ? set::height(328) : set::height(jsRaw('window.getTableHeight')),
                set::rowHeight(32),
                set::bordered(true),
                set::cols($groupHeader),
                set::data(array_values($groupData)),
                set::footPager(usePager('dtablePager')),
                $headerGroup ? set::plugins(array('header-group')) : null,
                set::onRenderCell(jsRaw('window.renderDTableCell')),
                set::loadPartial(true)
            )
        )
    ) : null,
    $echartOptions ? div
    (
        setClass('chart-side'),
        div
        (
            setClass('chart-type'),
            picker
            (
                set::name('chartType'),
                set::items($chartTypeList),
                set::value('line'),
                set::required(true),
                set::onchange("window.handleChartTypeChange($metric->id, '$viewType')")
            )
        ),
        div
        (
            setClass("chart chart-{$viewType}"),
            echarts
            (
                set::xAxis($echartOptions['xAxis']),
                set::yAxis($echartOptions['yAxis']),
                set::legend($echartOptions['legend']),
                set::series($echartOptions['series']),
                isset($echartOptions['dataZoom']) ? set::dataZoom($echartOptions['dataZoom']) : null,
                set::grid($echartOptions['grid']),
                set::tooltip($echartOptions['tooltip'])
            )->size('100%', '100%')
        )
    ) : null
);
