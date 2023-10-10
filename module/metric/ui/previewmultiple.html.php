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
            '{name}',
        ),
        button
        (
            setClass('picker-deselect-btn size-sm square ghost {multiple}'),
            set('onclick', 'window.handleRemoveLabel({id})'),
            span
            (
                setClass('close'),
            ),
        ),
    ),
);

div
(
    set::id('metricBox-tpl'),
    setClass('hidden'),
    div
    (
        set::id('metricBox{id}'),
        set('metric-id', '{id}'),
        setClass('metricBox'),
        div
        (
            setClass('metric-name metric-name-notfirst flex flex-between items-center'),
            div
            (
                span
                (
                    setClass('metric-name-weight'),
                    "{name}",
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
                        'url'         => helper::createLink('metric', 'details', "metricID=$current->id"),
                        'data-toggle' => 'modal'
                    ))) : null,
                    item(set(array
                    (
                        'text'    => $this->lang->metric->remove,
                        'class'   => 'ghost metric-remove',
                        'onclick' => "window.handleRemoveLabel({id})"
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
                    setClass('dtable'),
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
                )
            )
        ),
    ),
);

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
        set::url(helper::createLink('metric', 'preview', "scope=$exchangeScope&viewType=single&metricID={$current->id}")),
        $lang->metric->viewType->single,
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

$metricCheckItems = array();
foreach($metrics as $key => $metric)
{
    $class  = $metric->id == $current->id ? 'metric-current' : '';
    $class .= " font-medium metric-{$metric->id} checkbox";
    $metricCheckItems[] = item
    (
        set::text($metric->name),
        set::value($metric->id),
        set::scope($metric->scope),
        set::typeClass($class),
        set::checked($metric->id == $current->id),
        bind::change('window.handleCheckboxChange($element)'),
    );
}

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
            ),
        ),
        div
        (
            setClass('metric-tree'),
            checkList
            (
                set::className('check-list-metric'),
                set::primary(true),
                set::name('metric'),
                set::inline(false),
                $metricCheckItems,
            ),
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
            setClass('checked-content'),
            row
            (
                set::align('center'),
                cell
                (
                    setClass('checked-label-content'),
                    set::flex('auto'),
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
                            html(sprintf($lang->metric->selectCount, 1)),
                        ),
                        btn
                        (
                            setClass('btn ghost square size-sm rounded primary-hover-500 dropdown-icon visibility-hidden'),
                            set::icon('angle-double-right'),
                            set::iconClass('icon-18'),
                        ),
                        on::click('.dropdown-icon', 'setDropDown()'),
                    ),
                ),
            ),
        ),
        div
        (
            setClass('table-and-charts'),
            div
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
                            isset($current) ? $current->name : null,
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
                            setClass('chart chart-multiple'),
                        )
                    ),
                ),
            ),
        ),
    ),
);

form
(
    set::id('queryFormTpl'),
    setClass('hidden'),
    formRow
    (
        set::width('full'),
        formGroup
        (
            setClass('query-inline query-scope hidden picker-nowrap'),
            set::width('248px'),
            set::label($this->lang->metric->scope),
            set::name('scope'),
            set::items(array()),
        ),
        formGroup
        (
            setClass('query-inline query-date-range hidden'),
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
        ),
        formGroup
        (
            setClass('query-inline query-calc-time-range hidden'),
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
        ),
        formGroup
        (
            setClass('query-inline query-calc-time hidden'),
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
        ),
        formGroup
        (
            setClass('query-btn'),
            btn
            (
                setClass('btn secondary'),
                set::text($this->lang->metric->query->action),
            ),
        )
    ),
    set::actions(array()),
);

render();
