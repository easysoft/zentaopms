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

$fnGenerateQueryForm = function() use($metricRecordType, $metric, $metricID)
{
    if(!$metricRecordType) return null;
    $formGroups = array();
    if($metric->scope != 'system') $objectPairs = $this->metric->getPairsByScope($metric->scope);

    if($metricRecordType == 'scope' || $metricRecordType == 'scope-date')
    {
        $formGroups[] = formGroup
        (
            setClass('query-inline picker-nowrap'),
            set::width('248px'),
            set::label($this->lang->metric->query->scope[$metric->scope]),
            set::name('scope'),
            set::control(array('type' => 'picker', 'multiple' => true)),
            set::items($objectPairs)
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
                    set('id', 'dateBegin' . $metricID)
                ),
                $this->lang->metric->to,
                datePicker
                (
                    set::name('dateEnd'),
                    set('id', 'dateEnd' . $metricID)
                )
            )
        );
    }

    return form
    (
        set::id('queryForm' . $metricID),
        formRow
        (
            set::width('full'),
            $formGroups,
            !empty($formGroups) ? formGroup
            (
                setClass('query-btn'),
                btn
                (
                    setClass('btn secondary'),
                    set::text($this->lang->metric->query->action),
                    set::onclick("window.handleQueryClick($metricID, 'multiple')")
                )
            ) : null
        ),
        set::actions(array())
    );
};

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
                $metric->name
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
                    set::onRenderCell(jsRaw('window.renderDTableCell'))
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
                    set::onchange("window.handleChartTypeChange($metricID, 'multiple')")
                ) : null
            ),
            div
            (
                setClass('chart chart-multiple'),
                $echartOptions ? echarts
                (
                    set::xAxis($echartOptions['xAxis']),
                    set::yAxis($echartOptions['yAxis']),
                    set::series($echartOptions['series'])
                )->size('100%', '100%') : null
            )
        )
    )
);
