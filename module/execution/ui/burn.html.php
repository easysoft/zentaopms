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
featureBar
(
    btn
    (
        set
        (
            array
            (
                'class' => 'btn primary mr-5',
                'url' => '#',
                'icon' => 'refresh',
            ),
        ),
        $lang->execution->computeBurn
    ),
    li
    (
        setClass('nav-item'),
        a
        (
            set::id('weekend'),
            set::href('#'),
            $lang->execution->withweekend
        )
    ),
    li
    (
        setClass('nav-item'),
        a
        (
            set::id('delay'),
            set::href('#'),
            $lang->execution->withdelay
        )
    ),
    li
    (
        setClass('nav-item'),
        a
        (
            set::id('delay'),
            set
            (
                array
                (
                    'id'  => 'delay',
                    'url' => $this->createLink('execution', 'fixFirst', "id=$execution->id"),
                    'data-toggle' => 'modal',
                ),
            ),
            $lang->execution->fixFirst
        )
    ),
    li
    (
        setClass('nav-item'),
        html($lang->execution->howToUpdateBurn)
    ),
);

panel
(
    h2
    (
        setClass('text-center'),
        $executionName . ' ' . $this->lang->execution->burn . '(' . zget($lang->execution->burnByList, $burnBy) . ')',
        isset($execution->delay) ? label(setClass('label danger-outline ml-3'), $lang->execution->delayed) : null,
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
                'boundaryGap' => false
            )
        ),
        set::yAxis
        (
            array
            (
                'type'     => 'value',
                'name'     => $burnBy == 'storyPoint' ?  "({$lang->execution->storyPoint})" : "({$lang->execution->workHour})",
                'axisLine' => array('show' => true),
            )
        ),
        set::legend
        (
            array
            (
                'data' => array($lang->execution->charts->burn->graph->actuality, $lang->execution->charts->burn->graph->reference, $lang->execution->charts->burn->graph->delay),
            )
        ),
        set::tooltip
        (
            array
            (
                'trigger' => 'axis',
            )
        ),
        set::series
        (
            array
            (
                array
                (
                    'data' => $chartData['burnLine'],
                    'type' => 'line',
                    'name' => $lang->execution->charts->burn->graph->actuality
                ),
                array
                (
                    'data' => $chartData['baseLine'],
                    'type' => 'line',
                    'name' => $lang->execution->charts->burn->graph->reference,
                    'itemStyle' => array
                    (
                        'normal' => array
                        (
                            'color' => '#ddd',
                            'lineStyle' => array
                            (
                                'type'  => 'dashed',
                                'color' => '#ddd',
                            )
                        )
                    )
                ),
                array
                (
                    'data' => $chartData['delayLine'],
                    'type' => 'line',
                    'name' => $lang->execution->charts->burn->graph->delay,
                    'itemStyle' => array
                    (
                        'normal' => array
                        (
                            'color' => '#f00',
                            'lineStyle' => array
                            (
                                'color' => '#f00',
                            )
                        )
                    )
                ),
            )
        ),
    )->size('100%', 500),
);
