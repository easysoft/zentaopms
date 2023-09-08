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

$fnGenerateSide = function() use($metrics, $current, $viewType, $scope)
{
    $metricList = array();
    foreach($metrics as $key => $metric)
    {
        $class = $metric->id == $current->id ? 'metric-current' : '';
        $metricList[] = li
            (
                set::className($class . ' metric-item font-medium'),
                a(
                    $metric->name,
                    set::href(helper::createLink('metric', 'preview', "scope=$scope&viewType=$viewType&metricID={$metric->id}")),
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

toolbar
(
    btn
    (
        setClass('btn text-black ghost primary-hover-500'),
        set::icon('exchange'),
        set::iconClass('icon-18'),
        set::url(helper::createLink('metric', 'preview', "scope=$scope&viewType=multiple&metricID={$current->id}")),
        $lang->metric->viewType->multiple,
    ),
    common::hasPriv('metric', 'preview') ? btn
    (
        setClass('btn primary'),
        set::url(helper::createLink('metric', 'browse')),
        $lang->metric->manage
    ) : null,
);



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
                $metricList
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
    div
    (
        setClass('canvas'),
        div
        (
            setClass('metric-name flex flex-between items-center'),
            div
            (
                span
                (
                    setClass('metric-name-weight'),
                    isset($current) ? $current->name : null,
                ),
            ),
            div
            (
                setClass('flex-start'),
                toolbar
                (
                    haspriv('bug', 'report') ? item(set(array
                    (
                        'text'  => $this->lang->metric->details,
                        'class' => 'ghost details',
                        'url'   => '#',
                    ))) : null,
                    haspriv('bug', 'report') ? item(set(array
                    (
                        'icon'  => 'menu-backend',
                        'text'  => $this->lang->metric->filters,
                        'class' => 'ghost',
                        'url'   => '#',
                    ))) : null,
                    haspriv('bug', 'report') ? item(set(array
                    (
                        'icon'  => 'chart-line',
                        'text'  => $this->lang->metric->zAnalysis,
                        'class' => 'ghost chart-line-margin',
                        'url'   => '#',
                    ))) : null,

                )
            ),
        ),
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
                setClass('chart-side chart-center'),
                'chart'
            ),
        ),
    ),
);

render();
