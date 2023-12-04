<?php
declare(strict_types=1);
/**
 * The preview multiple file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();
foreach($lang->metric->featureBar['preview'] as $value => $text)
{
    $isActive = $value == $scope;
    $badge = $isActive && $recTotal != '' ? array('text' => $recTotal, 'class' => 'size-sm rounded-full white') : null;
    $items[] = item
    (
        set::id($value),
        set::text($text),
        set::active($isActive),
        set::badge($badge),
        bind::click('window.handleNavMenuClick($element)')
    );
}

div
(
    set::id('item-tpl'),
    setClass('hidden'),
    span
    (
        setClass('{spanClass}'),
        set('metric-id', '{id}'),
        div
        (
             setClass('gray-pale-div'),
             set('title', '{name}'),
            '{name}'
        ),
        button
        (
            setClass('picker-deselect-btn size-sm square ghost {multiple}'),
            set('onclick', 'window.handleRemoveLabel({id})'),
            span
            (
                setClass('close')
            )
        )
    )
);

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
nav
(
    set::className('nav-feature nav-ajax'),
    $items,
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
                setClass('checked'),
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
        set::url(helper::createLink('metric', 'preview', "scope=$exchangeScope&viewType=single&metricID={$current->id}")),
        $lang->metric->viewType->single
    )
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
            set::name('scope_' . $current->id),
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
            // set::label($this->lang->metric->date),
            inputGroup
            (
                datePicker
                (
                    setClass('query-date-picker'),
                    set::name('dateBegin'),
                    set('id', 'dateBegin' . $current->id),
                    set::placeholder($this->lang->metric->placeholder->select)
                ),
                $this->lang->metric->to,
                datePicker
                (
                    setClass('query-date-picker'),
                    set::name('dateEnd'),
                    set('id', 'dateEnd' . $current->id),
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
                    set::onclick("window.handleQueryClick($current->id, 'multiple')")
                )
            ) : null
        ),
        set::actions(array())
    );
};


$metricTrees = array();
foreach($groupMetrics as $key => $metrics)
{
    if(empty($metrics)) continue;
    $metricCheckItems = array();
    foreach($metrics as $metric)
    {
        $class  = $metric->id == $current->id ? 'metric-current' : '';
        $class .= ' font-medium checkbox';
        $metricCheckItems[] = item
            (
                set::text($metric->name),
                set::value($metric->id),
                set::scope($metric->scope),
                set::typeClass($class),
                set::checked($metric->id == $current->id),
                bind::change('window.handleCheckboxChange($element)')
            );
    }
    if($scope != 'collect')
    {
        $metricCount  = count($metrics);
        $metricTrees[] = div(setClass('check-list-title') ,$this->lang->metric->objectList[$key] . "($metricCount)");
    }
    $metricTrees[] = checkList
        (
            set::className('check-list-metric'),
            set::primary(true),
            set::name('metric'),
            set::inline(false),
            $metricCheckItems
        );
}

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
                    setClass('name-color side-title'),
                    $metricList
                )
            ),
            div
            (
                setClass('metric-tree'),
                $metricTrees
            )
        ),
    )
);

$star = (!empty($current->collector) and strpos($current->collector, ',' . $app->user->account . ',') !== false) ? 'star' : 'star-empty';
$metricBoxs = div
(
    set::id('metricBox' . $current->id),
    set('metric-id', $current->id),
    setClass('metricBox'),
    div
    (
        setClass('metric-name flex flex-between items-center'),
        div
        (
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
                    'text'  => $this->lang->metric->details,
                    'class' => 'ghost details',
                    'url'         => helper::createLink('metric', 'details', "metricID=$current->id"),
                    'data-toggle' => 'modal'
                ))) : null,
                item(set(array
                (
                    'text'    => $this->lang->metric->remove,
                    'class'   => 'ghost metric-remove hidden',
                    'onclick' => "window.handleRemoveLabel($current->id)"
                ))),
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
        setClass('table-and-chart table-and-chart-multiple'),
        div
        (
            setClass('table-side'),
            setStyle(array('flex-basis' => $tableWidth . 'px')),
            div
            (
                $groupData ? dtable
                (
                    set::height(310),
                    set::bordered(true),
                    set::cols($groupHeader),
                    set::data(array_values($groupData)),
                    ($metricRecordType == 'scope' || $metricRecordType == 'scope-date') ? set::footPager(usePager('dtablePager')) : null,
                    $headerGroup ? set::plugins(array('header-group')) : null,
                    set::onRenderCell(jsRaw('window.renderDTableCell')),
                    set::loadPartial(true)
                ) : null
            )
        ),
        div
        (
            setClass('chart-side'),
            div
            (
                setClass('chart-type'),
                $echartOptions ? picker
                (
                    set::name('chartType'),
                    set::items($chartTypeList),
                    set::value('line'),
                    set::required(true),
                    set::onchange("window.handleChartTypeChange($current->id, 'multiple')")
                ) : null
            ),
            div
            (
                setClass('chart chart-multiple'),
                $echartOptions ? echarts
                (
                    set::xAxis($echartOptions['xAxis']),
                    set::yAxis($echartOptions['yAxis']),
                    set::legend($echartOptions['legend']),
                    set::series($echartOptions['series']),
                    isset($echartOptions['dataZoom']) ? set::dataZoom($echartOptions['dataZoom']) : null,
                    set::grid($echartOptions['grid']),
                    set::tooltip($echartOptions['tooltip'])
                )->size('100%', '100%') : null
            )
        )
    )
);

div
(
    setClass('main'),
    div
    (
        setClass('canvas'),
        div
        (
            setClass('checked-content'),
            row
            (
                set::align('center'),
                cell
                (
                    setClass('checked-label-content'),
                    set::flex('auto')
                ),
                cell
                (
                    set::width(130),
                    set::flex('none'),
                    div
                    (
                        setClass('checked-label-right'),
                        span
                        (
                            setClass('checked-tip'),
                            html(sprintf($lang->metric->selectCount, 1))
                        ),
                        btn
                        (
                            setClass('btn ghost square size-sm rounded primary-hover-500 dropdown-icon visibility-hidden'),
                            set::icon('angle-double-right'),
                            set::iconClass('icon-18')
                        ),
                        on::click('.dropdown-icon', 'setDropDown()')
                    )
                )
            )
        ),
        div
        (
            setClass('table-and-charts'),
            $metricBoxs
        )
    )
);

render();
