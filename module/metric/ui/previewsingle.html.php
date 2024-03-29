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
include 'component/tableandcharts.html.php';
include 'component/queryform.html.php';
include 'component/filterpanel.html.php';

$currentID = $current->id ?? 0;

$fnGenerateSide = function() use($groupMetrics, $current, $viewType, $scope, $lang, $filtersBase64)
{
    $metricList = array();
    foreach($groupMetrics as $key => $metrics)
    {
        if(empty($metrics)) continue;

        if($scope != 'collect')
        {
            $metricCount  = count($metrics);
            $metricList[] = li(set::className('metric-group'), $lang->metric->objectList[$key] . "($metricCount)");
        }

        foreach($metrics as $metric)
        {
            $class = $metric->id == $current->id ? 'metric-current' : '';
            $params = "scope=$scope&viewType=$viewType&metricID={$metric->id}";
            if(!empty($filtersBase64)) $params .= "&filtersBase64={$filtersBase64}";

            $metricList[] = li
            (
                set::className($class . ' metric-item font-medium'),
                a(
                    $metric->name,
                    set::href(helper::createLink('metric', 'preview', $params))
                )
            );
        }
    }

    return ul($metricList);
};

$filterItems = $this->metric->buildFilterCheckList($filters);
featureBar
(
    set::load(''),
    set::current($scope),
    set::linkParams("scope={key}"),
    $fnGenerateFilterContent($filterItems)
);

$firstScope = current(array_keys($this->lang->metric->featureBar['preview']));
$exchangeScope = $scope == 'filter' ? $firstScope : $scope;
toolbar
(
    set::id('topbar'),
    btn
    (
        setClass('btn text-black ghost primary-hover-500'),
        set::icon('exchange'),
        set::iconClass('icon-18'),
        set::url(helper::createLink('metric', 'preview', "scope=$exchangeScope&viewType=multiple&metricID={$currentID}")),
        $lang->metric->viewType->multiple,
    )
);

$sideTitle = $scope == 'filter' ? sprintf($lang->metric->filter->filterTotal, count($metrics)) : $metricList;
$star = (!empty($current->collector) and strpos($current->collector, ',' . $app->user->account . ',') !== false) ? 'star' : 'star-empty';

sidebar
(
    set::width('25%'),
    set::onToggle(jsRaw("window.handleSidebarToggle")),
    div
    (
        setClass('side'),
        div
        (
            setClass('canvas'),
            div
            (
                setClass('title flex items-center'),
                span
                (
                    setClass('name-color'),
                    $sideTitle
                )
            ),
            div
            (
                setClass('metric-tree'),
                $fnGenerateSide($groupMetrics, $current, $viewType, $scope, $lang)
            )
        ),
    )
);
div
(
    setClass('main'),
    empty($current) ? div(setClass('canvas')) :
    div
    (
        setClass('canvas'),
        div
        (
            setClass('metric-name flex flex-between items-center'),
            div
            (
                setClass('name-and-star'),
                span
                (
                    setClass('metric-name-weight'),
                    isset($current) ? $current->name : null
                ),
                btn
                (
                    setClass('metric-collect metric-collect-' . $current->id),
                    set::type('link'),
                    set::icon($star),
                    set::iconClass($star),
                    set::square(true),
                    set::size('sm'),
                    set::title($lang->metric->collectStar),
                    on::click('.metric-collect', "window.collectMetric({$current->id})")
                )
            ),
            div
            (
                setClass('flex-start'),
                toolbar
                (
                    haspriv('metric', 'details') ? item(set(array
                    (
                        'text'        => $this->lang->metric->details,
                        'class'       => 'ghost details',
                        'url'         => helper::createLink('metric', 'details', "metricID=$current->id"),
                        'data-toggle' => 'modal'
                    ))) : null,
                    haspriv('metric', 'filters') ? item(set(array
                    (
                        'icon'  => 'menu-backend',
                        'text'  => $this->lang->metric->filters,
                        'class' => 'ghost hidden',
                        'url'   => '#'
                    ))) : null,
                    haspriv('metric', 'zAnalysis') ? item(set(array
                    (
                        'icon'  => 'chart-line',
                        'text'  => $this->lang->metric->zAnalysis,
                        'class' => 'ghost chart-line-margin hidden',
                        'url'   => '#'
                    ))) : null
                )
            )
        ),
        $fnGenerateQueryForm($viewType),
        $fnGenerateTableAndCharts($current)
    )
);

render();
