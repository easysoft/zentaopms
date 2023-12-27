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

$active  = isset($params['active']) ? $params['active'] : key($products); // 当前产品 ID。
$product = null;        // 当前产品。 Current active product.
$items   = array();     // 产品导航列表。 Product nav list.

foreach($products as $productItem)
{
    $projectID = isset($params['projectID']) ? $params['projectID'] : 0;
    $params    = helper::safe64Encode("module={$block->module}&projectID={$projectID}&active={$productItem->id}");
    $items[]   = array
    (
        'id'        => $productItem->id,
        'text'      => $productItem->name,
        'url'       => createLink('product', 'browse', "productID=$productItem->id"),
        'activeUrl' => createLink('block', 'printBlock', "blockID=$block->id&params=$params")
    );
    if($productItem->id == $active) $product = $productItem;
}

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

$monthFinish  = !empty($product) ? $product->monthFinish : array();
$monthCreated = !empty($product) ? $product->monthCreated : array();

statisticBlock
(
    set::block($block),
    set::active($active),
    set::moreLink(createLink('product', 'all', 'browseType=' . $block->params->type)),
    set::items($items),
    div
    (
        setClass($longBlock ? 'row' : 'col gap-3', 'h-full overflow-hidden items-stretch p-2'),
        center
        (
            setClass('flex-1 gap-4'),
            progressCircle
            (
                set::percent($product->storyDeliveryRate),
                set::size(112),
                set::text(false),
                set::circleWidth(0.06),
                div(span(setClass('text-2xl font-bold'), $product->storyDeliveryRate), '%'),
                div
                (
                    setClass('row text-sm text-gray items-center gap-1'),
                    $lang->block->productstatistic->deliveryRate,
                    icon
                    (
                        setClass('text-light text-sm'),
                        toggle::tooltip
                        (
                            array
                            (
                                'title'     => $lang->block->tooltips['deliveryRate'],
                                'placement' => 'bottom',
                                'type'      => 'white',
                                'className' => 'text-dark border border-light leading-5'
                            )
                        ),
                        'help'
                    )
                )
            ),
            row
            (
                setClass('justify-center items-center gap-4'),
                center
                (
                    div
                    (
                        common::hasPriv('product', 'browse') && $product->totalStories ? a
                        (
                            set('href', helper::createLink('product', 'browse', "productID={$product->id}&branch=all&browseType=allStory&param=0&storyType=story")),
                            $product->totalStories
                        ) : span($product->totalStories)
                    ),
                    div
                    (
                        span
                        (
                            setClass('text-sm text-gray'),
                            $lang->block->productstatistic->effectiveStory,
                            toggle::tooltip
                            (
                                array
                                (
                                    'title'     => $lang->block->tooltips['effectiveStory'],
                                    'placement' => 'bottom',
                                    'type'      => 'white',
                                    'className' => 'text-dark border border-light leading-5'
                                )
                            )
                        )
                    )
                ),
                center
                (
                    div
                    (
                        common::hasPriv('product', 'browse') && $product->closedStories ? a
                        (
                            set('href', helper::createLink('product', 'browse', "productID={$product->id}&branch=all&browseType=closedstory&param=0&storyType=story")),
                            $product->closedStories
                        ) : span($product->closedStories)
                    ),
                    div
                    (
                        span
                        (
                            setClass('text-sm text-gray'),
                            $lang->block->productstatistic->delivered,
                            toggle::tooltip
                            (
                                array
                                (
                                    'title'     => $lang->block->tooltips['deliveredStory'],
                                    'placement' => 'bottom',
                                    'type'      => 'white',
                                    'className' => 'text-dark border border-light leading-5'
                                )
                            )
                        )
                    )
                ),
                center
                (
                    div
                    (
                        common::hasPriv('product', 'browse') && $product->unclosedStories ? a
                        (
                            set('href', helper::createLink('product', 'browse', "productID={$product->id}&branch=all&browseType=unclosed&param=0&storyType=story")),
                            $product->unclosedStories
                        ) : span($product->unclosedStories)
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
        col
        (
            setClass('flex-1 gap-1.5 pr-3 py-2', $longBlock ? '' : 'pl-3'),
            div($lang->block->productstatistic->storyStatistics),
            row
            (
                setClass('text-sm text-gray gap-2'),
                html(sprintf($lang->block->productstatistic->monthDone, !empty($monthFinish[$lang->datepicker->dpText->TEXT_THIS_MONTH]) ? $monthFinish[$lang->datepicker->dpText->TEXT_THIS_MONTH] : 0)),
                divider(),
                html(sprintf($lang->block->productstatistic->monthOpened, !empty($monthCreated[$lang->datepicker->dpText->TEXT_THIS_MONTH]) ? $monthCreated[$lang->datepicker->dpText->TEXT_THIS_MONTH] : 0))
            ),
            echarts
            (
                set::color(array('#2B80FF', '#17CE97')),
                set::grid(array('left' => '10px', 'top' => '30px', 'right' => '0', 'bottom' => '0',  'containLabel' => true)),
                set::legend(array('show' => true, 'right' => '0')),
                set::xAxis(array('type' => 'category', 'data' => array_keys($monthFinish), 'splitLine' => array('show' => false), 'axisTick' => array('alignWithLabel' => true, 'interval' => '0'))),
                set::yAxis(array('type' => 'value', 'name' => $lang->number, 'splitLine' => array('show' => false), 'axisLine' => array('show' => true, 'color' => '#DDD'))),
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
            )->size('100%', 170),
        ),
        ($product->newPlan || $product->newExecution || $product->newRelease) ? col
        (
            setClass('flex-1 gap-4 pr-3 py-2', $longBlock ? 'border-l pl-4' : 'pl-3'),
            div($lang->block->productstatistic->news),
            $product->newPlan ? div
            (
                setClass($longBlock ? 'col' : 'row', 'gap-2'),
                div(setClass('text-sm'), $lang->block->productstatistic->newPlan),
                row
                (
                    setClass('gap-2'),
                    hasPriv('productplan', 'view') ? a
                    (
                        set('href', helper::createLink('productplan', 'view', "planID={$product->newPlan->id}")),
                        $product->newPlan->title
                    ) : $product->newPlan->title,
                    label
                    (
                        setClass('gray-pale rounded-full px-1'),
                        zget($lang->productplan->statusList, $product->newPlan->status)
                    )
                )
            ) : null,
            $product->newExecution ? div
            (
                setClass($longBlock ? 'col' : 'row', 'gap-2'),
                div(setClass('text-sm'), $lang->block->productstatistic->newExecution),
                row
                (
                    setClass('gap-2'),
                    hasPriv('execution', 'task') ? a
                    (
                        set('href', helper::createLink('execution', 'task', "executionID={$product->newExecution->id}")),
                        $product->newExecution->name
                    ) : $product->newExecution->name,
                    label
                    (
                        setClass('important-pale rounded-full'),
                        zget($lang->execution->statusList, $product->newExecution->status)
                    )
                )
            ) : null,
            $product->newRelease ? div
            (
                setClass($longBlock ? 'col' : 'row', 'gap-2'),
                div(setClass('text-sm'), $lang->block->productstatistic->newRelease),
                row
                (
                    setClass('gap-2'),
                    hasPriv('release', 'view') ? a
                    (
                        set('href', helper::createLink('release', 'view', "releaseID={$product->newRelease->id}")),
                        $product->newRelease->name
                    ) : $product->newRelease->name,
                    label
                    (
                        setClass('rounded-full', ($product->newRelease->status == 'normal' ? 'success-pale' : 'gray-pale')),
                        zget($lang->release->statusList, $product->newRelease->status)
                    )
                )
            ) : null
        ) : null
    )
);
