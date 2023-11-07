<?php
declare(strict_types = 1);
/**
 * The project deviation view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$getDeviationHtml = function(float $deviation): string
{
    if($deviation > 0) return "<span class='up'>&uarr;</span>" . $deviation;
    if($deviation < 0) return "<span class='down'>&darr;</span>" . abs($deviation);
    return "<span class='zero'>0</span>";
};

$getDeviationRateHtml = function(float|string $deviationRate): string
{
    if($deviationRate == 'n/a') return "<span class='zero'>" . $deviationRate . '</span>';
    if($deviationRate == 0)     return "<span class='zero'>" . $deviationRate . '%</span>';
    if($deviationRate >= 50)    return "<span class='u50'>" . $deviationRate . '%</span>';
    if($deviationRate >= 30)    return "<span class='u30'>" . $deviationRate . '%</span>';
    if($deviationRate >= 10)    return "<span class='u10'>" . $deviationRate . '%</span>';
    if($deviationRate > 0)      return "<span class='u0'>" . $deviationRate . '%</span>';
    if($deviationRate <= -20)   return "<span class='d20'>" . abs($deviationRate) . '%</span>';
    return "<span class='d0'>" . abs($deviationRate) . '%</span>';
};

$canView = hasPriv('execution', 'view');

$chartData = array();
foreach($executions as $execution)
{
    $chartData['labels'][] = $execution->executionName;
    $chartData['data'][]   = $execution->deviation;

    if($execution->multiple)
    {
        if($canView) $execution->executionName = html::a(helper::createLink('execution', 'view', "executionID={$execution->executionID}"), $execution->executionName);
    }
    else
    {
        $execution->executionName = $this->lang->null;
    }

    $execution->deviation     = $getDeviationHtml($execution->deviation);
    $execution->deviationRate = $getDeviationRateHtml($execution->deviationRate);
}

$cols = $config->pivot->dtable->projectDeviation->fieldList;

$generateData = function() use ($lang, $title, $cols, $executions, $chartData, $begin, $end)
{
    return array
    (
        div
        (
            setID('conditions'),
            setClass('flex gap-4 bg-canvas p-2'),
            on::change('loadProjectDeviation'),
            inputGroup
            (
                setClass('w-1/4'),
                $lang->pivot->execution . $lang->pivot->begin,
                datePicker(set(array('name' => 'begin', 'value' => $begin)))
            ),
            inputGroup
            (
                setClass('w-1/4'),
                $lang->pivot->execution . $lang->pivot->end,
                datePicker(set(array('name' => 'end', 'value' => $end)))
            )
        ),
        panel
        (
            setID('pivotPanel'),
            set::title($title),
            set::shadow(false),
            set::bodyClass('pt-0'),
            to::titleSuffix(
                icon
                (
                    setClass('cursor-pointer'),
                    setData(array('toggle' => 'tooltip', 'title' => $lang->pivot->deviationDesc, 'placement' => 'right', 'className' => 'text-gray border border-light', 'type' => 'white')),
                    'help'
                )
            ),
            dtable
            (
                set::striped(true),
                set::bordered(true),
                set::cols($cols),
                set::data($executions),
                set::emptyTip($lang->error->noData),
                set::height(jsRaw('getHeight')),
            )
        ),
        panel
        (
            setID('pivotChart'),
            set::title($lang->pivot->deviationChart),
            set::shadow(false),
            $chartData ? null : setClass('hidden'),
            $chartData ? echarts
            (
                set::xAxis
                (
                    array
                    (
                        'type' => 'category',
                        'boundaryGap' => false,
                        'axisLine' => array('onZero' => false),
                        'axisLabel' => array('interval' => 0),
                        'splitLine' => array('show' => true, 'interval' => 0),
                        'data' => $chartData['labels']
                    )
                ),
                set::yAxis
                (
                    array
                    (
                        'type'     => 'value',
                        'axisLine' => array('show' => true)
                    )
                ),
                set::tooltip
                (
                    array
                    (
                        'trigger' => 'axis',
                        'formatter' => '{b}: {c}h'
                    )
                ),
                set::series
                (
                    array
                    (
                        array
                        (
                            'data' => $chartData['data'],
                            'type' => 'line',
                            'lineStyle' => array('color' => '#0033CC')
                        )
                    )
                )
            )->size('100%', 300) : null
        )
    );
};
