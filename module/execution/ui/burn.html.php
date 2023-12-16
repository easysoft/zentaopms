<?php
declare(strict_types=1);
/**
 * The burn view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('workHour', $lang->execution->workHour);
jsVar('+type', str_replace(',', '%2C', $type));
jsVar('executionID', $executionID);

$weekend      = strpos($type, 'noweekend') !== false ? 'withweekend' : 'noweekend';
$delay        = strpos($type, 'withdelay') !== false ? 'nodelay' : 'withdelay';
$weekendParam = $delay == 'withdelay' ? "nodelay,{$weekend}" : "withdelay,{$weekend}";
$delayParam   = $weekend == 'noweekend' ? "withweekend,{$delay}" : "noweekend,{$delay}";
featureBar
(
    btn
    (
        set
        (
            array
            (
                'class' => 'btn primary mr-5 ajax-submit',
                'url' => createLink('execution', 'computeBurn', 'reload=yes'),
                'icon' => 'refresh'
            )
        ),
        $lang->execution->computeBurn
    ),
    li
    (
        setClass('nav-item'),
        a
        (
            set::id('weekend'),
            set::href(createLink('execution', 'burn', "executionID={$execution->id}&type={$weekendParam}&interval={$interval}")),
            $lang->execution->{$weekend}
        )
    ),
    li
    (
        setClass('nav-item'),
        a
        (
            set::id('delay'),
            set::href(createLink('execution', 'burn', "executionID={$execution->id}&type={$delayParam}")),
            $lang->execution->{$delay}
        )
    ),
    common::canModify('execution', $execution) ? li
    (
        setClass('nav-item'),
        a
        (
            set
            (
                array
                (
                    'href' => createLink('execution', 'fixFirst', "id={$execution->id}"),
                    'data-toggle' => 'modal'
                )
            ),
            $lang->execution->fixFirst
        )
    ) : null,
    li
    (
        setClass('nav-item mr-3'),
        html($lang->execution->howToUpdateBurn)
    ),
    li
    (
        set::className('burnByBox'),
        picker
        (
            set::id('burnBy'),
            set::name('burnBy'),
            set::items($lang->execution->burnByList),
            set::value($burnBy),
            set::required(true)
        )
    ),
    $interval ? li
    (
        set::className('intervalBox ml-4'),
        picker
        (
            set::id('interval'),
            set::name('interval'),
            set::items($dayList),
            set::value($interval),
            set::required(true)
        )
    ) : null
);

panel
(
    h2
    (
        setClass('flex items-center justify-center'),
        $executionName . ' ' . $this->lang->execution->burn . '(' . zget($lang->execution->burnByList, $burnBy) . ')',
        isset($execution->delay) ? label(setClass('label danger-pale ring-danger ml-3'), $lang->execution->delayed) : null
    ),
    echarts
    (
        set::xAxis
        (
            array
            (
                'type' => 'category',
                'data' => $chartData['labels'],
                'name' => $lang->execution->burnXUnit,
                'boundaryGap' => false,
                'axisLabel' => array('interval' => (int)$interval)
            )
        ),
        set::yAxis
        (
            array
            (
                'type'     => 'value',
                'name'     => $burnBy == 'storyPoint' ?  "({$lang->execution->storyPoint})" : "({$lang->execution->workHour})",
                'axisLine' => array('show' => true)
            )
        ),
        set::legend
        (
            array
            (
                'selectedMode' => false,
                'orient' => 'vertical',
                'left' => 'right',
                'top' => 'center',
                'align' => 'left',
                'data' => array($lang->execution->charts->burn->graph->actuality, $lang->execution->charts->burn->graph->reference, $lang->execution->charts->burn->graph->delay)
            )
        ),
        set::tooltip
        (
            array
            (
                'trigger' => 'axis',
                'axisPointer' => array(
                    'type' => 'none'
                ),
                'formatter' => "RAWJS<function(rowDatas){return window.randTipInfo(rowDatas);}>RAWJS"
            )
        ),
        set::series
        (
            array
            (
                array
                (
                    'data' => $chartData['baseLine'],
                    'type' => 'line',
                    'name' => $lang->execution->charts->burn->graph->reference,
                    'symbolSize' => 8,
                    'symbol' => 'circle',
                    'itemStyle' => array
                    (
                        'normal' => array
                        (
                            'color' => '#D8D8D8',
                            'lineStyle' => array
                            (
                                'width' => 3,
                                'color' => '#F1F1F1'
                            )
                        ),
                        'emphasis' => array
                        (
                            'color' => '#FFF',
                            'borderColor' => '#D8D8D8',
                            'borderWidth' => 2
                        )
                    ),
                    'emphasis' => array
                    (
                        'lineStyle' => array
                        (
                            'width' => 3,
                            'color' => '#F1F1F1'
                        )
                    )
                ),
                array
                (
                    'data' => $chartData['burnLine'],
                    'type' => 'line',
                    'name' => $lang->execution->charts->burn->graph->actuality,
                    'symbolSize' => 8,
                    'symbol' => 'circle',
                    'itemStyle' => array
                    (
                        'normal' => array
                        (
                            'color' => '#006AF1',
                            'lineStyle' => array
                            (
                                'width' => 3,
                                'color' => '#2B7DFE'
                            )
                        ),
                        'emphasis' => array
                        (
                            'color' => '#fff',
                            'borderColor' => '#006AF1',
                            'borderWidth' => 2
                        )
                    )
                ),
                strpos($type, 'withdelay') !== false ? array
                (
                    'data' => empty($chartData['delayLine']) ? array() : $chartData['delayLine'],
                    'type' => 'line',
                    'name' => $lang->execution->charts->burn->graph->delay,
                    'symbolSize' => 8,
                    'symbol' => 'circle',
                    'itemStyle' => array
                    (
                        'normal' => array
                        (
                            'color' => '#F00',
                            'lineStyle' => array
                            (
                                'color' => '#F00'
                            )
                        ),
                        'emphasis' => array
                        (
                            'color' => '#fff',
                            'borderColor' => '#F00',
                            'borderWidth' => 2
                        )
                    ),
                ) : null
            )
        )
    )->size('100%', 500),
);
