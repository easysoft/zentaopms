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

$fnGenerateSide = function() use($metrics, $current, $viewType, $scope, $filtersBase64)
{
    $metricList = array();
    foreach($metrics as $key => $metric)
    {
        $class = $metric->id == $current->id ? 'metric-current' : '';
        $class .= " metric-{$metric->id}";
        $params = "scope=$scope&viewType=$viewType&metricID={$metric->id}";
        if(!empty($filtersBase64)) $params .= "&filtersBase64={$filtersBase64}";

        $metricList[] = li
            (
                set::className($class . ' metric-item font-medium'),
                a(
                    $metric->name,
                    set::href(helper::createLink('metric', 'preview', $params)),
                )
            );
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
        'onclick' => 'window.handleFilterClearItem(this)',
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
            set::items($items),
        ),
        set::headingActions(array($removeAction)),
    );
};

$filterItems = $this->metric->buildFilterCheckList($filters);
featureBar
(
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
                setClass('checked'),
            ),
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
                    array('type' => 'default', 'text' => $lang->metric->filter->clear, 'onclick' => 'window.handleFilterClearAll(this)'),
                )
            ),
            $fnGenerateFilterPanel('scope',   $filterItems['scope']),
            $fnGenerateFilterPanel('object',  $filterItems['object']),
            $fnGenerateFilterPanel('purpose', $filterItems['purpose']),
        ),
    ),
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
        set::url(helper::createLink('metric', 'preview', "scope=$exchangeScope&viewType=multiple&metricID={$current->id}")),
        $lang->metric->viewType->multiple,
    ),
    /*
    common::hasPriv('metric', 'preview') ? btn
    (
        setClass('btn primary'),
        set::url(helper::createLink('metric', 'browse')),
        $lang->metric->manage
    ) : null,
    */
);

$metricRecordType = $this->metric->getMetricRecordType(current($resultData));

$fnGenerateQueryForm = function() use($metricRecordType, $current)
{
    if(!$metricRecordType) return null;
    $formGroups = array();
    if($current->scope != 'system') $objectPairs = $this->metric->getPairsByScope($current->scope);

    if($metricRecordType == 'scope' || $metricRecordType == 'scope-date')
    {
        $formGroups[] = formGroup
        (
            setClass('query-inline picker-nowrap'),
            set::width('248px'),
            set::label($this->lang->metric->query->scope[$current->scope]),
            set::name('scope'),
            set::control(array('type' => 'picker', 'multiple' => true)),
            set::items($objectPairs),
        );
    }

    if($metricRecordType == 'date' || $metricRecordType == 'scope-date')
    {
        $formGroups[] = formGroup
        (
            setClass('query-inline'),
            set::width('360px'),
            set::label($this->lang->metric->date),
            inputGroup
            (
                datePicker
                (
                    set::name('dateBegin'),
                    set('id', 'dateBegin'),
                ),
                $this->lang->metric->to,
                datePicker
                (
                    set::name('dateEnd'),
                    set('id', 'dateEnd'),
                ),
            ),
        );
    }

    if($metricRecordType == 'system')
    {
        $formGroups[] = formGroup
        (
            setClass('query-inline'),
            set::width('360px'),
            set::label($this->lang->metric->calcTime),
            inputGroup
            (
                datePicker
                (
                    set::name('calcBegin'),
                    set('id', 'calcBegin'),
                ),
                $this->lang->metric->to,
                datePicker
                (
                    set::name('calcEnd'),
                    set('id', 'calcEnd'),
                ),
            ),
        );
    }
    else
    {
        $formGroups[] = formGroup
        (
            setClass('query-inline'),
            set::width('200px'),
            set::label($this->lang->metric->calcTime),
            inputGroup
            (
                datePicker
                (
                    set::name('calcTime'),
                    set('id', 'calcTime'),
                    set::required(true),
                    set::value(helper::today()),
                ),
            ),
        );
    }

    return form
    (
        set::id('queryForm'),
        formRow
        (
            set::width('full'),
            $formGroups,
            formGroup
            (
                setClass('query-btn'),
                btn
                (
                    setClass('btn secondary'),
                    set::text($this->lang->metric->query->action),
                    set::onclick('window.handleQueryClick()'),
                ),
            )
        ),
        set::actions(array()),
    );
};

$sideTitle = $scope == 'filter' ? sprintf($lang->metric->filter->filterTotal, count($metrics)) : $metricList;
$star = (!empty($current->collector) and strpos($current->collector, ',' . $app->user->account . ',') !== false) ? 'star' : 'star-empty';
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
            ),
        ),
        div
        (
            setClass('metric-tree'),
            $fnGenerateSide($metrics, $current, $viewType, $scope),
        ),
    ),
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
                    isset($current) ? $current->name : null,
                ),
                btn
                (
                    setClass('metric-collect'),
                    set::type('link'),
                    set::icon($star),
                    set::iconClass($star),
                    set::square(true),
                    set::size('sm'),
                    set::title($lang->metric->collectStar),
                    on::click('.metric-collect', "window.collectMetric({$current->id})"),
                ),
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
                        'url'   => '#',
                    ))) : null,
                    haspriv('metric', 'zAnalysis') ? item(set(array
                    (
                        'icon'  => 'chart-line',
                        'text'  => $this->lang->metric->zAnalysis,
                        'class' => 'ghost chart-line-margin hidden',
                        'url'   => '#',
                    ))) : null,

                )
            ),
        ),
        $fnGenerateQueryForm(),
        div
        (
            setClass('table-and-chart table-and-chart-single'),
            div
            (
                setClass('table-side'),
                div
                (
                    setClass('dtable'),
                )
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
                    setClass('chart chart-single'),
                ),
            ),
        ),
    ),
);

render();
