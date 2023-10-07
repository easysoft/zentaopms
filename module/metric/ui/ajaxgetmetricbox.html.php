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

$metricID = $metric->id;

div
(
    set::id('metricBox' . $metricID),
    set('metric-id', $metricID),
    setClass('metricBox'),
    div
    (
        setClass('metric-name metric-name-notfirst flex flex-between items-center'),
        div
        (
            span
            (
                setClass('metric-name-weight'),
                $metric->name,
            ),
        ),
        div
        (
            setClass('flex-start'),
            toolbar
            (
                haspriv('metric', 'details') ? item(set(array
                (
                    'text'  => $this->lang->metric->details,
                    'class' => 'ghost details',
                    'url'         => helper::createLink('metric', 'details', "metricID=$metricID"),
                    'data-toggle' => 'modal'
                ))) : null,
                item(set(array
                (
                    'text'    => $this->lang->metric->remove,
                    'class'   => 'ghost metric-remove',
                    'onclick' => "window.handleRemoveLabel($metricID)"
                ))),
                haspriv('metric', 'filters') ? item(set(array
                (
                    'icon'  => 'menu-backend',
                    'text'  => $this->lang->metric->filters,
                    'class' => 'ghost hidden',
                    'url'   => '#',
                ))) : null,
                haspriv('metric', 'zAnalysis') ? item(set(array
                (
                    'icon'  => 'chart-line',
                    'text'  => $this->lang->metric->zAnalysis,
                    'class' => 'ghost chart-line-margin hidden',
                    'url'   => '#',
                ))) : null,
            ),
        ),
    ),
    div
    (
        setClass('table-and-chart table-and-chart-multiple'),
        div
        (
            setClass('table-side'),
            div
            (
                $resultData ? dtable
                (
                    set::height(310),
                    set::bordered(true),
                    set::cols($resultHeader),
                    set::data(array_values($resultData)),
                    set::onRenderCell(jsRaw('window.renderDTableCell')),
                ) : null,

            ),
        ),
        div
        (
            setClass('chart-side'),
            div
            (
                setClass('chart-type'),
            ),
            div
            (
                setClass('chart chart-multiple'),
                $echartOptions ? echarts
                (
                    set::xAxis($echartOptions['xAxis']),
                    set::yAxis($echartOptions['yAxis']),
                    set::series($echartOptions['series']),
                )->size('100%', '100%') : null,
            )
        )
    ),
);
