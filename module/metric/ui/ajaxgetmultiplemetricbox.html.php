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

$metricID = $metric->id;

$fnGenerateQueryForm = function() use($metricRecordType, $metric, $metricID, $dateLabels, $defaultDate)
{
    if(!$metricRecordType) return null;
    $formGroups = array();
    if($metric->scope != 'system') $objectPairs = $this->metric->getPairsByScope($metric->scope);

    if($metricRecordType == 'scope' || $metricRecordType == 'scope-date')
    {
        $formGroups[] = formGroup
        (
            setClass('query-inline picker-nowrap w-40'),
            set::label($this->lang->metric->query->scope[$metric->scope]),
            set::name('scope_' . $metricID),
            set::control(array('type' => 'picker', 'multiple' => true)),
            set::items($objectPairs),
            set::placeholder($this->lang->metric->placeholder->{$metric->scope})
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
                    set('id', 'dateBegin' . $metricID),
                    set::placeholder($this->lang->metric->placeholder->select)
                ),
                $this->lang->metric->to,
                datePicker
                (
                    setClass('query-date-picker'),
                    set::name('dateEnd'),
                    set('id', 'dateEnd' . $metricID),
                    set::placeholder($this->lang->metric->placeholder->select)
                )
            ),
            on::change('.query-date-picker', 'window.handleDatePickerChange(target)'),
        );
    }

    return form
    (
        set::id('queryForm' . $metricID),
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
                    set::onclick("window.handleQueryClick($metricID, 'multiple')")
                )
            ) : null
        ),
        set::actions(array())
    );
};

$star = (!empty($metric->collector) and strpos($metric->collector, ',' . $app->user->account . ',') !== false) ? 'star' : 'star-empty';
div
(
    setClass('metric-name metric-name-notfirst flex flex-between items-center'),
    div
    (
        span
        (
            setClass('metric-name-weight'),
            $metric->name
        ),
        btn
        (
            setClass('metric-collect metric-collect-' . $metricID),
            set::type('link'),
            set::icon($star),
            set::iconClass($star),
            set::square(true),
            set::size('sm'),
            set::title($lang->metric->collectStar),
            on::click('.metric-collect', "window.collectMetric({$metricID})")
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
);
$fnGenerateQueryForm();
div
(
    setClass('table-and-chart table-and-chart-multiple'),
    $groupData ? div
    (
        setClass('table-side'),
        setStyle(array('flex-basis' => $tableWidth . 'px')),
        div
        (
            dtable
            (
                set::height(328),
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
                set::onchange("window.handleChartTypeChange($metricID, 'multiple')")
            )
        ),
        div
        (
            setClass('chart chart-multiple'),
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
