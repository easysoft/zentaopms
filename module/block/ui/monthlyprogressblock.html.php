<?php
declare(strict_types=1);
/**
* The monthlyprogress block view file of block module of ZenTaoPMS.
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
        setClass('flex h-full w-full' . ($longBlock ? ' flex-nowrap' : ' flex-wrap')),
        cell
        (
            setClass('chart line-chart ' . ($longBlock ? 'py-2' : 'py-1 w-full')),
            set::width($longBlock ? '1/3' : '100%'),
            echarts
            (
                set::title(array('text' => $lang->block->monthlyprogress->doneStoryEstimateTrendChart, 'textStyle' => array('fontSize' => '12'))),
                set::color(array('#2B80FF', '#17CE97')),
                set::grid(array('left' => '10px', 'top' => '60px', 'right' => '0', 'bottom' => '0',  'containLabel' => true)),
                set::xAxis(array('type' => 'category', 'data' => array_keys($doneStoryEstimate), 'splitLine' => array('show' => false), 'axisTick' => array('alignWithLabel' => true, 'interval' => 0), 'axisLabel' => array('fontSize' => $longBlock ? '8' : '10'))),
                set::yAxis(array('type' => 'value', 'name' => "({$config->block->storyUnitList[$config->custom->hourPoint]})", 'splitLine' => array('show' => false), 'axisLine' => array('show' => true, 'color' => '#DDD'), 'axisLabel' => array('showMaxLabel' => true, 'interval' => 'auto'))),
                set::series
                (
                    array
                    (
                        'type' => 'line',
                        'data' => array_values($doneStoryEstimate)
                    )
                )
            )->size('100%', 200)
        ),
        cell
        (
            setClass('chart line-chart ' . ($longBlock ? 'py-2' : 'py-1 w-full')),
            set::width($longBlock ? '1/3' : '100%'),
            echarts
            (
                set::title(array('text' => $lang->block->monthlyprogress->storyTrendChart, 'textStyle' => array('fontSize' => '12'))),
                set::color(array('#2B80FF', '#17CE97')),
                set::grid(array('left' => '10px', 'top' => '60px', 'right' => '0', 'bottom' => '0',  'containLabel' => true)),
                set::legend(array('show' => true, 'right' => '0', 'top' => '25px', 'textStyle' => array('fontSize' => '11'))),
                set::xAxis(array('type' => 'category', 'data' => array_keys($createStoryCount), 'splitLine' => array('show' => false), 'axisTick' => array('alignWithLabel' => true, 'interval' => 0), 'axisLabel' => array('fontSize' => $longBlock ? '8' : '10'))),
                set::yAxis(array('type' => 'value', 'name' => "({$lang->block->projectstatistic->unit})", 'splitLine' => array('show' => false), 'axisLine' => array('show' => true, 'color' => '#DDD'), 'axisLabel' => array('showMaxLabel' => true, 'interval' => 'auto'))),
                set::series
                (
                    array
                    (
                        array
                        (
                            'type' => 'line',
                            'name' => $lang->block->productstatistic->opened,
                            'data' => array_values($createStoryCount)
                        ),
                        array
                        (
                            'type' => 'line',
                            'name' => $lang->block->productstatistic->done,
                            'data' => array_values($doneStoryCount)
                        )
                    )
                )
            )->size('100%', 200)
        ),
        cell
        (
            setClass('chart line-chart ' . ($longBlock ? 'py-2' : 'py-1 w-full')),
            set::width($longBlock ? '1/3' : '100%'),
            echarts
            (
                set::title(array('text' => $lang->block->monthlyprogress->bugTrendChart, 'textStyle' => array('fontSize' => '12'))),
                set::color(array('#2B80FF', '#17CE97')),
                set::grid(array('left' => '10px', 'top' => '60px', 'right' => '0', 'bottom' => '0',  'containLabel' => true)),
                set::legend(array('show' => true, 'right' => '0', 'top' => '25px', 'textStyle' => array('fontSize' => '11'))),
                set::xAxis(array('type' => 'category', 'data' => array_keys($createBugCount), 'splitLine' => array('show' => false), 'axisTick' => array('alignWithLabel' => true, 'interval' => 0), 'axisLabel' => array('fontSize' => $longBlock ? '8' : '10'))),
                set::yAxis(array('type' => 'value', 'name' => "({$lang->block->projectstatistic->unit})", 'splitLine' => array('show' => false), 'axisLine' => array('show' => true, 'color' => '#DDD'), 'axisLabel' => array('showMaxLabel' => true, 'interval' => 'auto'))),
                set::series
                (
                    array
                    (
                        array
                        (
                            'type' => 'line',
                            'name' => $lang->block->productstatistic->opened,
                            'data' => array_values($createBugCount)
                        ),
                        array
                        (
                            'type' => 'line',
                            'name' => $lang->bug->resolve,
                            'data' => array_values($fixedBugCount)
                        )
                    )
                )
            )->size('100%', 200)
        )
    )
);

render();
