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


$fnGenerateFilterPanel = function($code, $filterItem) use($lang)
{
    $panelClass = $filterItem['class'];
    $items      = $filterItem['items'];

    $removeAction = array
    (
        'class' => 'text-primary ghost',
        'text'  => sprintf($lang->metric->filter->clearAction, $lang->metric->filter->$code),
        'onclick' => 'window.handleFilterClearItem(this)'
    );
    return panel
    (
        setClass($panelClass),
        set::headingClass('clear-padding'),
        set::bodyClass('clear-padding'),
        set::title($lang->metric->filter->$code),
        checkList
        (
            set::primary(true),
            set::name($code),
            set::inline(true),
            set::items($items)
        ),
        set::headingActions(array($removeAction))
    );
};

$filterItems = $this->metric->buildFilterCheckList($filters);
featureBar
(
    set::load(''),
    set::current($scope),
    set::linkParams("scope={key}"),
    li
    (
        btn
        (
            setClass('btn ghost filter-btn'),
            set::icon('search'),
            bind::click('window.handleFilterToggle($element)'),
            span
            (
                setClass('common'),
                $lang->metric->filter->common
            ),
            span
            (
                setClass('checked')
            )
        ),
        panel
        (
            setClass('filter-panel hidden'),
            set::footerClass('filter-actions'),
            set::footerActions
            (
                array
                (
                    array('type' => 'primary', 'text' => $lang->metric->filter->common, 'onclick' => 'window.handleFilterClick(this)'),
                    array('type' => 'default', 'text' => $lang->metric->filter->clear, 'onclick' => 'window.handleFilterClearAll(this)')
                )
            ),
            $fnGenerateFilterPanel('scope',   $filterItems['scope']),
            $fnGenerateFilterPanel('object',  $filterItems['object']),
            $fnGenerateFilterPanel('purpose', $filterItems['purpose'])
        )
    )
);

$firstScope = current(array_keys($this->lang->metric->featureBar['preview']));
$exchangeScope = $scope == 'filter' ? $firstScope : $scope;
toolbar
(
    btn
    (
        setClass('btn text-black ghost primary-hover-500'),
        set::icon('exchange'),
        set::iconClass('icon-18'),
        set::url(helper::createLink('metric', 'preview', "scope=$exchangeScope&viewType=multiple&metricID={$currentID}")),
        $lang->metric->viewType->multiple,
    ),
    /*
    common::hasPriv('metric', 'preview') ? btn
    (
        setClass('btn primary'),
        set::url(helper::createLink('metric', 'browse')),
        $lang->metric->manage
    ) : null
    */
);

$fnGenerateQueryForm = function() use($metricRecordType, $current, $dateLabels, $defaultDate)
{
    if(!$metricRecordType) return null;
    $formGroups = array();
    if($current->scope != 'system') $objectPairs = $this->metric->getPairsByScope($current->scope);

    if($metricRecordType == 'scope' || $metricRecordType == 'scope-date')
    {
        $formGroups[] = formGroup
        (
            setClass('query-inline picker-nowrap w-40'),
            set::label($this->lang->metric->query->scope[$current->scope]),
            set::name('scope'),
            set::control(array('type' => 'picker', 'multiple' => true)),
            set::items($objectPairs),
            set::placeholder($this->lang->metric->placeholder->{$current->scope})
        );
    }

    if($metricRecordType == 'scope' || $metricRecordType == 'system')
    {
        $btnLabels = array();
        foreach($this->lang->metric->query->dayLabels as $key => $label)
        {
            $active = $key == '7' ? ' selected' : '';
            $btnLabels[] = btn
            (
                setClass("$active default w-16 p-0"),
                set::key($key),
                $label
            );
        }
        $formGroups[] = formGroup
        (
            setClass('query-calc-date query-inline w-64'),
            btngroup
            (
                $btnLabels
            ),
            on::click('.query-calc-date button.btn', 'window.handleCalcDateClick(target)'),
        );
    }

    if($metricRecordType == 'date' || $metricRecordType == 'scope-date')
    {
        $btnLabels = array();
        foreach($dateLabels as $key => $label)
        {
            $active = $key == $defaultDate ? ' selected' : '';
            $btnLabels[] = btn
            (
                setClass("$active default w-16 p-0"),
                set::key($key),
                $label
            );
        }
        $formGroups[] = formGroup
        (
            setClass('query-date query-inline w-64'),
            btngroup
            (
                $btnLabels
            ),
            on::click('.query-date button.btn', 'window.handleDateLabelClick(target)'),
        );

        $formGroups[] = formGroup
        (
            setClass('query-inline w-80'),
            inputGroup
            (
                datePicker
                (
                    setClass('query-date-picker'),
                    set::name('dateBegin'),
                    set('id', 'dateBegin'),
                    set::placeholder($this->lang->metric->placeholder->select)
                ),
                $this->lang->metric->to,
                datePicker
                (
                    setClass('query-date-picker'),
                    set::name('dateEnd'),
                    set('id', 'dateEnd'),
                    set::placeholder($this->lang->metric->placeholder->select)
                )
            ),
            on::change('.query-date-picker', 'window.handleDatePickerChange(target)'),
        );
    }

    return form
    (
        set::id('queryForm' . $current->id),
        setClass('ml-4'),
        formRow
        (
            set::width('max'),
            $formGroups,
            !empty($formGroups) ? formGroup
            (
                setClass('query-btn'),
                btn
                (
                    setClass('btn secondary'),
                    set::text($this->lang->metric->query->action),
                    set::onclick("window.handleQueryClick($current->id, 'single')")
                )
            ) : null
        ),
        set::actions(array())
    );
};

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
        $fnGenerateQueryForm(),
        div
        (
            setClass('table-and-chart table-and-chart-single'),
            $groupData ? div
            (
                setClass('table-side'),
                setStyle(array('flex-basis' => $tableWidth . 'px')),
                div
                (
                    dtable
                    (
                        set::bordered(true),
                        set::height(jsRaw('window.getTableHeight')),
                        set::rowHeight(32),
                        set::cols($groupHeader),
                        set::data(array_values($groupData)),
                        set::footPager(usePager('dtablePager', $pagerExtra)),
                        $headerGroup ? set::plugins(array('header-group')) : null,
                        set::onRenderCell(jsRaw('window.renderDTableCell'))
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
                        set::onchange("window.handleChartTypeChange($current->id, 'single')")
                    )
                ),
                div
                (
                    setClass('chart chart-single'),
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
        )
    )
);

render();
