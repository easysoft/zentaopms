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

/**
 * 获取区块左侧的产品列表。
 * Get product tabs on the left side.
 *
 * @param  array  $products
 * @param  string $blockNavCode
 * @param  bool   $longBlock
 * @return array
 */
$getProductTabs = function(array $products, string $blockNavCode, bool $longBlock): array
{
    $navTabs  = array();
    $selected = key($products);
    $navTabs[] = li
    (
        set('class', 'nav-item overflow-hidden nav-prev rounded-full bg-white shadow-md h-6 w-6'),
        a(icon(set('size', '24'), 'angle-left'))
    );
    foreach($products as $product)
    {
        $navTabs[] = li
        (
            set('class', 'nav-item nav-switch w-full'),
            a
            (
                set('class', 'ellipsis text-dark title ' . ($longBlock && $product->id == $selected ? ' active' : '')),
                $longBlock ? set('data-toggle', 'tab') : null,
                set('data-name', "tab3{$blockNavCode}Content{$product->id}"),
                set('href', $longBlock ? "#tab3{$blockNavCode}Content{$product->id}" : helper::createLink('product', 'browse', "productID=$product->id")),
                $product->name

            ),
            !$longBlock ? a
            (
                set('class', 'hidden' . ($product->id == $selected ? ' active' : '')),
                set('data-toggle', 'tab'),
                set('data-name', "tab3{$blockNavCode}Content{$product->id}"),
                set('href', "#tab3{$blockNavCode}Content{$product->id}"),
            ) : null,
            a
            (
                set('class', 'link flex-1 text-right hidden'),
                set('href', helper::createLink('product', 'browse', "productID=$product->id")),
                icon
                (
                    set('class', 'rotate-90 text-primary'),
                    setStyle(array('--tw-rotate' => '270deg')),
                    'import'
                )
            )
        );
    }
    $navTabs[] = li
    (
        set('class', 'nav-item overflow-hidden nav-next rounded-full bg-white shadow-md h-6 w-6'),
        a(icon(set('size', '24'), 'angle-right'))
    );
    return $navTabs;
};

/**
 * 获取区块右侧显示的产品信息。
 * Get product statistical information.
 *
 * @param  array  $products
 * @param  string $blockNavID
 * @param  bool   $longBlock
 * @return array
 */
$getProductInfo = function(array $products, string $blockNavID, bool $longBlock): array
{
    global $lang;

    $selected = key($products);
    $tabItems = array();
    foreach($products as $product)
    {
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

        $monthFinish  = $product->monthFinish;
        $monthCreated = $product->monthCreated;
        $tabItems[]   = div
        (
            set('class', 'tab-pane h-full' . ($product->id == $selected ? ' active' : '')),
            set('id', "tab3{$blockNavID}Content{$product->id}"),
            div
            (
                set('class', 'flex h-full ' . ($longBlock ? '' : 'col')),
                cell
                (
                    set('class', 'flex-1'),
                    $longBlock ? set('width', '70%') : null,
                    div
                    (
                        set('class', 'flex h-full ' . ($longBlock ? '' : 'col')),
                        cell
                        (
                            $longBlock ? set('width', '40%') : null,
                            set('class', $longBlock ? 'p-4' : 'px-4'),
                            div
                            (
                                set('class', 'chart pie-chart ' . ($longBlock ? 'py-6' : 'py-1')),
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
                                    setClass('pie-chart-title text-center h-0'),
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
                                                toggle::tooltip(array('title' => '提示文本')),
                                            )
                                        )
                                    )
                                )
                            ),
                            div
                            (
                                set('class', 'flex h-full story-num w-44'),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
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
                                            set('class', 'text-sm text-gray'),
                                            $lang->block->productstatistic->effectiveStory
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
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
                                            set('class', 'text-sm text-gray'),
                                            $lang->block->productstatistic->delivered
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
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
                                            set('class', 'text-sm text-gray'),
                                            $lang->block->productstatistic->unclosed
                                        )
                                    )
                                )
                            )
                        ),
                        cell
                        (
                            $longBlock ? set('width', '60%') : null,
                            set('class', 'py-4'),
                            div
                            (
                                set('class', 'border-r'),
                                div
                                (
                                    set('class', 'px-4 pb-2'),
                                    $lang->block->productstatistic->storyStatistics
                                ),
                                div
                                (
                                    set('class', 'px-4'),
                                    span
                                    (
                                        set('class', 'border-r pr-2 text-sm text-gray'),
                                        html(sprintf($lang->block->productstatistic->monthDone, !empty($monthFinish[$lang->datepicker->dpText->TEXT_THIS_MONTH]) ? $monthFinish[$lang->datepicker->dpText->TEXT_THIS_MONTH] : 0))
                                    ),
                                    span
                                    (
                                        set('class', 'pl-2 text-sm text-gray'),
                                        html(sprintf($lang->block->productstatistic->monthOpened, !empty($monthCreated[$lang->datepicker->dpText->TEXT_THIS_MONTH]) ? $monthCreated[$lang->datepicker->dpText->TEXT_THIS_MONTH] : 0))
                                    )
                                ),
                                div
                                (
                                    set('class', 'px-4 py-2 chart'),
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
                                )
                            )
                        )
                    )
                ),
                ($product->newPlan || $product->newExecution || $product->newRelease) ? cell
                (
                    set('width', '30%'),
                    set('class', 'p-4'),
                    div
                    (
                        set('class', 'pb-2'),
                        span($lang->block->productstatistic->news)
                    ),
                    $product->newPlan ? div
                    (
                        set('class', 'pb-4' . ($longBlock ? '' : 'flex')),
                        div(span(set('class', 'text-sm text-gray'), $lang->block->productstatistic->newPlan)),
                        div
                        (
                            set('class', $longBlock ? 'py-1' : 'pl-2'),
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
                                set('class', 'label lighter-pale rounded-full ml-2 px-1'),
                                zget($lang->productplan->statusList, $product->newPlan->status)
                            )
                        )
                    ) : null,
                    $product->newExecution ? div
                    (
                        set('class', 'pb-4 ' . ($longBlock ? '' : 'flex')),
                        div(span(set('class', 'text-sm text-gray'), $lang->block->productstatistic->newExecution)),
                        div
                        (
                            set('class', $longBlock ? 'py-1' : 'pl-2'),
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
                                set('class', 'label important-pale rounded-full ml-2'),
                                zget($lang->execution->statusList, $product->newExecution->status)
                            )
                        )
                    ) : null,
                    $product->newRelease ? div
                    (
                        set('class', $longBlock ? '' : 'flex'),
                        div(span(set('class', 'text-sm text-gray'), $lang->block->productstatistic->newRelease)),
                        div
                        (
                            set('class', $longBlock ? 'py-1' : 'pl-2'),
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
                                set('class', 'label rounded-full ml-2 ' . ($product->newRelease->status == 'normal' ? 'success-pale' : 'lighter-pale')),
                                zget($lang->release->statusList, $product->newRelease->status)
                            )
                        )
                    ) : null
                ) : null
            )
        );
    }
    return $tabItems;
};

$blockNavCode = 'nav-' . uniqid();
panel
(
    set('id', "productstatistic-block-{$block->id}"),
    on::click('.nav-prev,.nav-next', 'switchNav'),
    set('class', 'productstatistic-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set('headingClass', 'border-b'),
    set::title($block->title),
    to::headingActions
    (
        a
        (
            set('class', 'text-gray'),
            set('href', createLink('product', 'all', 'browseType=' . $block->params->type)),
            $lang->more,
            icon('caret-right')
        )
    ),
    div
    (
        set('class', "flex h-full overflow-hidden " . ($longBlock ? '' : 'col')),
        cell
        (
            $longBlock ? set('width', '22%') : null,
            set('class', $longBlock ? 'bg-secondary-pale overflow-y-auto overflow-x-hidden' : ''),
            ul
            (
                set('class', 'nav nav-tabs ' .  ($longBlock ? 'nav-stacked' : 'pt-4 px-4')),
                $getProductTabs($products, $blockNavCode, $longBlock)
            ),
        ),
        cell
        (
            set('class', 'tab-content'),
            set('width', '78%'),
            $getProductInfo($products, $blockNavCode, $longBlock)
        )
    )
);
render();
