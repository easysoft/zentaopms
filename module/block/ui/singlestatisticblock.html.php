<?php
declare(strict_types=1);
/**
* The product statistic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;
$app->loadLang('execution');

$doneData   = array();
$openedData = array();
foreach($product->monthFinish as $date => $count)
{
    if($date == date('Y-m'))
    {
        $product->monthFinish[$lang->datepicker->dpText->TEXT_THIS_MONTH] = $count;
        unset($product->monthFinish[$date]);
    }
    $doneData[] = $count;
}
foreach($product->monthCreated as $date => $count)
{
    if($date == date('Y-m'))
    {
        $product->monthCreated[$lang->datepicker->dpText->TEXT_THIS_MONTH] = $count;
        unset($product->monthCreated[$date]);
    }
    $openedData[] = $count;
}

panel
(
    setClass('singleproductstatistic-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set::headingClass('border-b'),
    set::title($block->title),
    div
    (
        setClass("flex h-full overflow-hidden " . ($longBlock ? '' : 'col')),
        cell
        (
            setClass('flex-1'),
            $longBlock ? set('width', '70%') : null,
            div
            (
                setClass('flex h-full ' . ($longBlock ? '' : 'col')),
                cell
                (
                    $longBlock ? set('width', '40%') : null,
                    setClass($longBlock ? 'p-4' : 'px-4'),
                    div
                    (
                        setClass('chart pie-chart ' . ($longBlock ? 'py-6' : 'py-1')),
                        echarts
                        (
                            set::color(array('#2B80FF', '#E3E4E9')),
                            set::series
                            (
                                array
                                (
                                    array
                                    (
                                        'type'   => 'pie',
                                        'radius' => array('80%', '90%'),
                                        'itemStyle' => array('borderRadius' => '40'),
                                        'label'  => array('show' => false),
                                        'data'   => array($product->storyDeliveryRate, 100 - $product->storyDeliveryRate)
                                    )
                                )
                            )
                        )->size('100%', 120),
                        div
                        (
                            set::className('pie-chart-title text-center h-0'),
                            div(span(set::className('text-2xl font-bold'), $product->storyDeliveryRate . '%')),
                            div
                            (
                                span
                                (
                                    setClass('text-sm text-gray'),
                                    $lang->block->productstatistic->deliveryRate,
                                    icon
                                    (
                                        'help',
                                        setClass('text-light'),
                                        toggle::tooltip(array('title' => '提示文本'))
                                    )
                                )
                            )
                        )
                    ),
                    div
                    (
                        setClass('flex h-full story-num w-44'),
                        cell
                        (
                            setClass('flex-1 text-center'),
                            div
                            (
                                common::hasPriv('product', 'browse') && $product->totalStories ? a
                                (
                                    set('href', helper::createLink('product', 'browse', "productID={$product->id}&branch=all&browseType=allStory&param=0&storyType=story")),
                                    $product->totalStories
                                ) : span
                                (
                                    $product->totalStories
                                )
                            ),
                            div
                            (
                                span
                                (
                                    setClass('text-sm text-gray'),
                                    $lang->block->productstatistic->effectiveStory
                                )
                            )
                        ),
                        cell
                        (
                            setClass('flex-1 text-center'),
                            div
                            (
                                common::hasPriv('product', 'browse') && $product->closedStories ? a
                                (
                                    set('href', helper::createLink('product', 'browse', "productID={$product->id}&branch=all&browseType=closedstory&param=0&storyType=story")),
                                    $product->closedStories
                                ) : span
                                (
                                    $product->closedStories
                                )
                            ),
                            div
                            (
                                span
                                (
                                    setClass('text-sm text-gray'),
                                    $lang->block->productstatistic->delivered
                                )
                            )
                        ),
                        cell
                        (
                            setClass('flex-1 text-center'),
                            div
                            (
                                common::hasPriv('product', 'browse') && $product->unclosedStories ? a
                                (
                                    set('href', helper::createLink('product', 'browse', "productID={$product->id}&branch=all&browseType=unclosed&param=0&storyType=story")),
                                    $product->unclosedStories
                                ) : span
                                (
                                    $product->unclosedStories
                                )
                            ),
                            div
                            (
                                span
                                (
                                    setClass('text-sm text-gray'),
                                    $lang->block->productstatistic->unclosed
                                )
                            )
                        )
                    )
                ),
                cell
                (
                    $longBlock ? set('width', '60%') : null,
                    setClass('py-4'),
                    div
                    (
                        setClass('border-r'),
                        div
                        (
                            setClass('px-4 pb-2'),
                            $lang->block->productstatistic->storyStatistics
                        ),
                        div
                        (
                            setClass('px-4'),
                            span
                            (
                                setClass('border-r pr-2 text-sm text-gray'),
                                html(sprintf($lang->block->productstatistic->monthDone, !empty($product->monthFinish[$lang->datepicker->dpText->TEXT_THIS_MONTH]) ? $product->monthFinish[$lang->datepicker->dpText->TEXT_THIS_MONTH] : 0))
                            ),
                            span
                            (
                                setClass('pl-2 text-sm text-gray'),
                                html(sprintf($lang->block->productstatistic->monthOpened, !empty($product->monthCreated[$lang->datepicker->dpText->TEXT_THIS_MONTH]) ? $product->monthCreated[$lang->datepicker->dpText->TEXT_THIS_MONTH] : 0))
                            )
                        ),
                        div
                        (
                            setClass('px-4 py-2 chart'),
                            echarts
                            (
                                set::color(array('#2B80FF', '#17CE97')),
                                set::grid(array('left' => '10px', 'top' => '30px', 'right' => '0', 'bottom' => '30px',  'containLabel' => true)),
                                set::legend(array('show' => true, 'right' => '0')),
                                set::xAxis(array('type' => 'category', 'data' => array_keys($product->monthFinish), 'splitLine' => array('show' => false), 'axisTick' => array('alignWithLabel' => true, 'interval' => '0'))),
                                set::yAxis(array('type' => 'value', 'name' => '个', 'splitLine' => array('show' => false), 'axisLine' => array('show' => true, 'color' => '#DDD'))),
                                set::series
                                (
                                    array
                                    (
                                        array
                                        (
                                            'type' => 'line',
                                            'name' => $lang->block->productstatistic->opened,
                                            'data' => $openedData
                                        ),
                                        array
                                        (
                                            'type' => 'line',
                                            'name' => $lang->block->productstatistic->done,
                                            'data' => $doneData
                                        )
                                    )
                                )
                            )->size('100%', 170)
                        )
                    )
                )
            )
        ),
        ($product->newPlan || $product->newExecution || $product->newRelease) ? cell
        (
            set('width', '30%'),
            setClass('p-4'),
            div
            (
                setClass('pb-2'),
                span($lang->block->productstatistic->news)
            ),
            $product->newPlan ? div
            (
                setClass('pb-4' . ($longBlock ? '' : 'flex')),
                div(span(setClass('text-sm text-gray'), $lang->block->productstatistic->newPlan)),
                div
                (
                    setClass($longBlock ? 'py-1' : 'pl-2'),
                    common::hasPriv('productplan', 'view') ? a
                    (
                        set('href', helper::createLink('productplan', 'view', "planID={$product->newPlan->id}")),
                        $product->newPlan->title
                    ) : span
                    (
                        $product->newPlan->title
                    ),
                    span
                    (
                        setClass('label lighter-pale rounded-full ml-2 px-1'),
                        zget($lang->productplan->statusList, $product->newPlan->status)
                    )
                )
            ) : null,
            $product->newExecution ? div
            (
                setClass('pb-4 ' . ($longBlock ? '' : 'flex')),
                div(span(setClass('text-sm text-gray'), $lang->block->productstatistic->newExecution)),
                div
                (
                    setClass($longBlock ? 'py-1' : 'pl-2'),
                    common::hasPriv('execution', 'task') ? a
                    (
                        set('href', helper::createLink('execution', 'task', "executionID={$product->newExecution->id}")),
                        $product->newExecution->name
                    ) : span
                    (
                        $product->newExecution->name
                    ),
                    span
                    (
                        setClass('label important-pale rounded-full ml-2'),
                        zget($lang->execution->statusList, $product->newExecution->status)
                    )
                )
            ) : null,
            $product->newRelease ? div
            (
                setClass($longBlock ? '' : 'flex'),
                div(span(setClass('text-sm text-gray'), $lang->block->productstatistic->newRelease)),
                div
                (
                    setClass($longBlock ? 'py-1' : 'pl-2'),
                    common::hasPriv('release', 'view') ? a
                    (
                        set('href', helper::createLink('release', 'view', "releaseID={$product->newRelease->id}")),
                        $product->newRelease->name
                    ) : span
                    (
                        $product->newRelease->name
                    ),
                    span
                    (
                        setClass('label rounded-full ml-2 ' . ($product->newRelease->status == 'normal' ? 'success-pale' : 'lighter-pale')),
                        zget($lang->release->statusList, $product->newRelease->status)
                    )
                )
            ) : null
        ) : null
    )
);
render();
