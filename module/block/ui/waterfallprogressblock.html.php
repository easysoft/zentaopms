<?php
declare(strict_types=1);
/**
* The waterfall progress block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

blockPanel
(
    div
    (
        setClass('pt-6'),
        echarts
        (
            set::color(array('#2B80FF', '#17CE97', '#FFAF65')),
            set::grid(array('left' => '10px', 'top' => '30px', 'right' => '0', 'bottom' => '0',  'containLabel' => true)),
            set::legend(array('show' => true, 'right' => '0')),
            set::xAxis(array('type' => 'category', 'data' => array_keys($charts['pv']), 'splitLine' => array('show' => false), 'axisTick' => array('alignWithLabel' => true, 'interval' => '0'))),
            set::yAxis(array('type' => 'value', 'name' => $lang->block->estimate->workhour, 'splitLine' => array('show' => false), 'axisLine' => array('show' => true, 'color' => '#DDD'))),
            set::series
            (
                array
                (
                    array
                    (
                        'type' => 'line',
                        'name' => 'PV',
                        'data' => array_values($charts['pv']),
                        'emphasis' => array('label' => array('show' => true))
                    ),
                    array
                    (
                        'type' => 'line',
                        'name' => 'EV',
                        'data' => array_values($charts['ev']),
                        'emphasis' => array('label' => array('show' => true))
                    ),
                    array
                    (
                        'type' => 'line',
                        'name' => 'AC',
                        'data' => array_values($charts['ac']),
                        'emphasis' => array('label' => array('show' => true))
                    )
                )
            )
        )->size('100%', 170),
    )
);
