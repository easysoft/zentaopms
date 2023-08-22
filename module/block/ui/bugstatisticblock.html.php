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

$uniqid         =  uniqid();
$blockNavCode   = 'nav-' . $uniqid;
$navTabs        = array();
$selected       = key($products);
$statisticCells = array();
$preproductID   = 0;
$nextproductID  = 0;
$productIdList  = array_keys($products);
$tabItems       = array();
foreach($products as $productID => $product)
{
    $productInfo = div
    (
        setClass('flex' . ($longBlock ? ' flex-nowrap' : ' flex-wrap')),
        cell
        (
            setClass('flex flex-wrap items-center content-center progress-circle'),
            set::width($longBlock ? '30%' : '100%'),
            div
            (
                setClass('flex justify-center w-full'),
                zui::progressCircle
                (
                    set('percent', zget($resolvedRate, $productID, 0)),
                    set('size', 112),
                    set('circleWidth', 6),
                    set('text', zget($resolvedRate, $productID, 0) . '%'),
                    set('textY', 50),
                    set('textStyle', 'font-size: 30px;'),
                ),
            ),
            div
            (
                setClass('flex justify-center w-full h-0'),
                span
                (
                    setClass('text-gray text-sm progress-text'),
                    $lang->block->qastatistic->fixBugRate,
                    icon
                    (
                        setClass('pl-0.5'),
                        toggle::tooltip(array('title' => '提示文本')),
                        'help'
                    ),
                ),
            ),
            cell
            (
                setClass('flex justify-center w-full mt-3 gap-x-4'),
                col
                (
                    span
                    (
                        setClass('flex justify-center'),
                        zget($totalBugs, $productID, 0),
                    ),
                    span
                    (
                        setClass('text-sm text-gray'),
                        $lang->block->bugstatistic->effective
                    ),
                ),
                col
                (
                    span
                    (
                        setClass('flex justify-center'),
                        zget($closedBugs, $productID, 0),
                    ),
                    span
                    (
                        setClass('text-sm text-gray'),
                        $lang->block->bugstatistic->fixed
                    ),
                ),
                col
                (
                    span
                    (
                        setClass('flex justify-center'),
                        zget($unresovledBugs, $productID, 0),
                    ),
                    span
                    (
                        setClass('text-sm text-gray'),
                        $lang->block->bugstatistic->activated
                    ),
                ),
            ),
        ),
        cell
        (
            setClass('chart bar-chart p-4' .  ($longBlock ? ' mt-3' : ' pb-0')),
            set::width($longBlock ? '70%' : '100%'),
            echarts
            (
                set::title(array('text' => $lang->block->qastatistic->bugStatusStat, 'textStyle' => array('fontSize' => '12'))),
                set::color(array('#66a2ff', '#7adfba', '#9ea3b0')),
                set::tooltip(array('trigger' => 'axis')),
                set::grid(array('left' => '10px', 'top' => '50px', 'right' => '0', 'bottom' => '0',  'containLabel' => true)),
                set::legend(array('show' => true, 'right' => '0', 'top' => '25px', 'textStyle' => array('fontSize' => '11'))),
                set::xAxis(array('type' => 'category', 'data' => $months, 'splitLine' => array('show' => false), 'axisTick' => array('alignWithLabel' => true, 'interval' => 0), 'axisLabel' => array('fontSize' => $longBlock ? '8' : '10'))),
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
                            'stack'    => 'Ad',
                            'name'     => $lang->bug->activate,
                            'data'     => array_values($activateBugs[$productID]),
                        ),
                        array
                        (
                            'type'  => 'bar',
                            'name'  => $lang->bug->resolve,
                            'stack' => 'Ad',
                            'data'  => array_values($resolveBugs[$productID]),
                        ),
                        array
                        (
                            'type'  => 'bar',
                            'name'  => $lang->bug->close,
                            'stack' => 'Ad',
                            'data'  => array_values($closeBugs[$productID]),
                        ),
                    ),
                ),
            )->size('100%', 200),
        ),
    );

    if($longBlock)
    {
        $navTabs[] = li
        (
            setClass('nav-item'),
            a
            (
                setClass('ellipsis title ' . ($product->id == $selected ? ' active' : '')),
                set('data-toggle', 'tab'),
                set::href("#tab{$blockNavCode}Content{$product->id}"),
                $product->name

            ),
            a
            (
                setClass('link flex-1 text-right hidden'),
                set::href(helper::createLink('product', 'browse', "productID={$product->id}")),
                icon
                (
                    setClass('rotate-90 text-primary'),
                    'export'
                )
            )
        );

        $tabItems[] = div
        (
            setClass('tab-pane' . ($product->id == $selected ? ' active' : '')),
            set('id', "tab{$blockNavCode}Content{$product->id}"),
            $productInfo,
        );
    }
    else
    {
        $index         = array_search($product->id, $productIdList);
        $nextProductID = $index !== false && !empty($productIdList[$index + 1]) ? $productIdList[$index + 1] : 0;

        $tabItems = array();
        $tabItems[] = cell
        (
            ul
            (
                setClass('nav nav-tabs h-10 px-1 justify-between items-center'),
                set::width('100%'),
                li
                (
                    setClass('nav-item'),
                    btn
                    (
                        setClass('size-sm shadow-lg circle pre-button'),
                        set::square(true),
                        set::disabled(empty($preProductID)),
                        set::href("#tab{$blockNavCode}Content{$preProductID}"),
                        set('data-toggle', 'tab'),
                        set::iconClass('text-xl text-primary'),
                        set::icon('angle-left'),
                    ),
                ),
                li
                (
                    setClass('nav-item px-4'),
                    hasPriv('product', 'browse') ? btn
                    (
                        setClass('ghost'),
                        set::url(createLink('product', 'browse', "productID={$product->id}")),
                        span
                        (
                            setClass('text-primary'),
                            $product->name
                        ),
                        icon
                        (
                            setClass('text-primary ml-4 rotate-90'),
                            'export'
                        ),
                    ) : $product->name,
                ),
                li
                (
                    setClass('nav-item'),
                    btn
                    (
                        setClass('size-sm shadow-lg circle next-button'),
                        set::square(true),
                        set::disabled(empty($nextProductID)),
                        set::href("#tab{$blockNavCode}Content{$nextProductID}"),
                        set('data-toggle', 'tab'),
                        set::iconClass('text-xl text-primary'),
                        set::icon('angle-right'),
                    ),
                ),
            ),
        );
        $tabItems[] = cell
        (
            setClass('tab-content pt-1'),
            set::width('100%'),
            div
            (
                $productInfo,
            ),
        );
        $statisticCells[] = cell
        (
            setClass('tab-pane pt-1 w-full' . ($product->id == $selected ? ' active' : '')),
            set('id', "tab{$blockNavCode}Content{$product->id}"),
            $tabItems,
        );
        $preProductID = $product->id;
    }
}
if($longBlock)
{
    $statisticCells[] = cell
    (
        set::width('22%'),
        setClass('bg-secondary-pale overflow-y-auto overflow-x-hidden'),
        ul
        (
            setClass('nav nav-tabs nav-stacked'),
            $navTabs,
        ),
    );
    $statisticCells[] = cell
    (
         setClass('tab-content'),
         set::width('78%'),
         $tabItems,
    );
}

panel
(
    setClass('bugstatistic-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set::bodyClass('no-shadow border-t p-0'),
    set::title($block->title),
    div
    (
        setClass('flex h-full' . (!$longBlock ? ' flex-wrap' : '')),
        $statisticCells,
    )
);

render();
