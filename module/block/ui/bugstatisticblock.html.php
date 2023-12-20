<?php
declare(strict_types=1);
/**
* The bugstatistic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$active  = isset($params['active']) ? $params['active'] : key($products);
$product = null;

$items = array();
foreach($products as $productItem)
{
    $projectID = isset($params['projectID']) ? $params['projectID'] : 0;
    $params    = helper::safe64Encode("module={$block->module}&projectID={$projectID}&active={$productItem->id}");
    $items[]   = array
    (
        'id'        => $productItem->id,
        'text'      => $productItem->name,
        'url'       => createLink('product', 'browse', "productID={$productItem->id}"),
        'activeUrl' => createLink('block', 'printBlock', "blockID={$block->id}&params={$params}")
    );
    if($productItem->id == $active) $product = $productItem;
}

statisticBlock
(
    set::block($block),
    set::active($active),
    set::items($items),
    div
    (
        setClass('flex' . ($longBlock ? ' flex-nowrap' : ' flex-wrap')),
        cell
        (
            setClass('flex flex-wrap items-center content-center progress-circle'),
            set::width($longBlock ? '30%' : '100%'),
            div
            (
                setClass('flex justify-center w-full'),
                progressCircle
                (
                    set::percent($resolvedRate),
                    set::size(112),
                    set::text(false),
                    set::circleWidth('0.06'),
                    div(span(setClass('text-2xl font-bold'), $resolvedRate), '%'),
                    div
                    (
                        setClass('row text-sm text-gray items-center gap-1'),
                        $lang->block->qastatistic->fixBugRate,
                        icon
                        (
                            setClass('text-light text-sm'),
                            toggle::tooltip
                            (
                                array
                                (
                                    'title'     => $lang->block->tooltips['resolvedRate'],
                                    'placement' => 'bottom',
                                    'type'      => 'white',
                                    'className' => 'text-dark border border-light leading-5'
                                )
                            ),
                            'help'
                        )
                    )
                )
            ),
            cell
            (
                setClass('flex justify-center w-full mt-3 gap-x-4'),
                col
                (
                    span
                    (
                        setClass('flex justify-center'),
                        $totalBugs
                    ),
                    span
                    (
                        setClass('text-sm text-gray'),
                        $lang->block->bugstatistic->effective
                    )
                ),
                col
                (
                    span
                    (
                        setClass('flex justify-center'),
                        $closedBugs
                    ),
                    span
                    (
                        setClass('text-sm text-gray'),
                        $lang->block->bugstatistic->fixed
                    )
                ),
                col
                (
                    span
                    (
                        setClass('flex justify-center'),
                        $unresovledBugs
                    ),
                    span
                    (
                        setClass('text-sm text-gray'),
                        $lang->block->bugstatistic->activated
                    )
                )
            )
        ),
        cell
        (
            setClass('chart bar-chart p-4' .  ($longBlock ? ' mt-3' : ' pb-0')),
            set::width($longBlock ? '70%' : '100%'),
            echarts
            (
                set::title(array('text' => $lang->block->qastatistic->bugStatusStat, 'textStyle' => array('fontSize' => '12'))),
                set::color(array('#66a2ff', '#9ea3b0')),
                set::tooltip(array('trigger' => 'axis')),
                set::grid(array('left' => '10px', 'top' => '50px', 'right' => '0', 'bottom' => '0',  'containLabel' => true)),
                set::legend(array('show' => true, 'right' => '0', 'top' => '25px', 'textStyle' => array('fontSize' => '11'))),
                set::xAxis(array('type' => 'category', 'data' => array_keys($activateBugs), 'splitLine' => array('show' => false), 'axisTick' => array('alignWithLabel' => true, 'interval' => 0), 'axisLabel' => array('fontSize' => $longBlock ? '8' : '10'))),
                set::yAxis(array('type' => 'value', 'splitLine' => array('show' => false), 'axisLine' => array('show' => true, 'color' => '#DDD'), 'axisLabel' => array('showMaxLabel' => true, 'interval' => 'auto'))),
                set::tooptop(array('show' => true, 'formatter' => '{b}: {c}')),
                set::series
                (
                    array
                    (
                        array
                        (
                            'type'     => 'bar',
                            'barWidth' => '8',
                            'name'     => $lang->bug->createAB,
                            'data'     => array_values($activateBugs)
                        ),
                        array
                        (
                            'type'     => 'bar',
                            'barWidth' => '8',
                            'name'     => $lang->bug->close,
                            'data'     => array_values($closeBugs)
                        )
                    )
                )
            )->size('100%', 200)
        )
    )
);

render();
