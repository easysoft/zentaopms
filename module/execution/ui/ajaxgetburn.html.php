<?php
declare(strict_types=1);
/**
 * The ajaxGetBurn view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;
panel
(
    div
    (
        set::class('flex flex-nowrap justify-between'),
        div
        (
            set('class', 'panel-title'),
            $execution->name . $lang->execution->burn,
            isset($execution->delay) ? label
            (
                setClass('danger-pale ring-danger ml-1'),
                $lang->execution->delayed
            ) : null,
        ),
        common::hasPriv('execution', 'burn') ? btn
        (
            setClass('ghost text-gray'),
            set::url(createLink('execution', 'burn', "executionID={$execution->id}")),
            $lang->more
        ) : null,
    ),
    echarts
    (
        set::grid(array('left' => '0', 'right' => '50px', 'bottom' => '0',  'containLabel' => true)),
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
                'name'     => "({$lang->execution->workHour})",
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
    )->size('100%', '85%')
);

/* ====== Render page ====== */
render();
