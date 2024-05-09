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
include 'component/tableandcharts.html.php';
include 'component/queryform.html.php';
include 'component/filterpanel.html.php';

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

$filterItems = $this->metric->buildFilterCheckList($filters);
nav
(
    set::className('nav-feature nav-ajax'),
    $items,
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
        set::url(helper::createLink('metric', 'preview', "scope=$exchangeScope&viewType=single&metricID={$current->id}")),
        $lang->metric->viewType->single
    )
);

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
    $fnGenerateQueryForm($viewType),
    $fnGenerateTableAndCharts($current)
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
