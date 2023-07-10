<?php
declare(strict_types=1);
/**
* The qa statistic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

/**
 * 获取区块左侧的产品列表.
 * Get product tabs on the left side.
 *
 * @param  array    $products
 * @param  string   $blockNavCode
 * @param  bool     $longBlock
 * @access public
 * @return array
 */
$getProductTabs = function(array $products, string $blockNavCode, bool $longBlock): array
{
    $navTabs  = array();
    $selected = key($products);
    foreach($products as $product)
    {
        $navTabs[] = li
        (
            set('class', 'nav-item'),
            a
            (
                set('class', 'ellipsis title ' . ($product->id == $selected ? 'active' : '')),
                set('data-toggle', 'tab'),
                set('data-name', "tab3{$blockNavCode}Content{$product->id}"),
                set('href', "#tab3{$blockNavCode}Content{$product->id}"),
                $product->name

            ),
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
    return $navTabs;
};

/**
 * 获取区块右侧显示的项目信息.
 * Get product statistical information.
 *
 * @param  object   $products
 * @param  string   $blockNavID
 * @param  bool     $longBlock
 * @access public
 * @return array
 */
$getProductInfo = function(array $products, string $blockNavID, bool $longBlock): array
{
    global $lang;

    $selected = key($products);
    $tabItems = array();
    foreach($products as $product)
    {
        $tabItems[] = div
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
                                                'data'   => array(69.9, 100-69.9)
                                            )
                                        )
                                    )
                                )->size('100%', 120),
                                div
                                (
                                    set::class('pie-chart-title text-center'),
                                    div(span(set::class('text-2xl font-bold'), '69.9%')),
                                    div(span(set::class('text-sm text-gray'), $lang->block->qastatistic->closedBugRate, icon('help', set('data-toggle', 'tooltip'), set('id', 'storyTip'), set('class', 'text-light'))))
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
                                        span(!empty($product->total) ? $product->total : 0)
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            $lang->block->qastatistic->totalBug
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div
                                    (
                                        span(!empty($product->closed) ? $product->closed : 0)
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            $lang->block->qastatistic->closedBug
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div
                                    (
                                        span(!empty($product->activated) ? $product->activated : 0)
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            $lang->block->qastatistic->activatedBug
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
                                div
                                (
                                    set('class', 'px-4 pb-2'),
                                    $lang->block->qastatistic->bugStatistics
                                ),
                                div
                                (
                                    set('class', 'flex border-r py-1'),
                                    cell
                                    (
                                        div(set('class', 'py-1'), span('昨日新增'), span(set('class', 'ml-1'), '12')),
                                        div(set('class', 'py-1'), span('今日新增'), span(set('class', 'ml-1'), '6')),
                                    ),
                                    cell
                                    (
                                        set('class', 'flex-1 px-3'),
                                        div
                                        (
                                            set('class', 'pt-2'),
                                            div
                                            (
                                                set('class', 'progress h-2'),
                                                div
                                                (
                                                    set('class', 'progress-bar secondary'),
                                                    set('role', 'progressbar'),
                                                    setStyle(['width' => '36%']),
                                                )
                                            )
                                        ),
                                        div
                                        (
                                            set('class', 'pt-5'),
                                            div
                                            (
                                                set('class', 'progress h-2'),
                                                div
                                                (
                                                    set('class', 'progress-bar'),
                                                    set('role', 'progressbar'),
                                                    setStyle(['width' => '18%']),
                                                )
                                            )
                                        )
                                    )
                                ),
                                div
                                (
                                    set('class', 'flex border-r py-1'),
                                    cell
                                    (
                                        div(set('class', 'py-1'), span('昨日解决'), span(set('class', 'ml-1'), '36')),
                                        div(set('class', 'py-1'), span('今日解决'), span(set('class', 'ml-1'), '12')),
                                    ),
                                    cell
                                    (
                                        set('class', 'flex-1 px-3'),
                                        div
                                        (
                                            set('class', 'pt-2'),
                                            div
                                            (
                                                set('class', 'progress h-2'),
                                                div
                                                (
                                                    set('class', 'progress-bar secondary'),
                                                    set('role', 'progressbar'),
                                                    setStyle(['width' => '84%']),
                                                )
                                            )
                                        ),
                                        div
                                        (
                                            set('class', 'pt-5'),
                                            div
                                            (
                                                set('class', 'progress h-2'),
                                                div
                                                (
                                                    set('class', 'progress-bar'),
                                                    set('role', 'progressbar'),
                                                    setStyle(['width' => '28%']),
                                                )
                                            )
                                        )
                                    )
                                ),
                                div
                                (
                                    set('class', 'flex border-r py-1'),
                                    cell
                                    (
                                        div(set('class', 'py-1'), span('昨日关闭'), span(set('class', 'ml-1'), '24')),
                                        div(set('class', 'py-1'), span('今日关闭'), span(set('class', 'ml-1'), '12')),
                                    ),
                                    cell
                                    (
                                        set('class', 'flex-1 px-3'),
                                        div
                                        (
                                            set('class', 'pt-2'),
                                            div
                                            (
                                                set('class', 'progress h-2'),
                                                div
                                                (
                                                    set('class', 'progress-bar secondary'),
                                                    set('role', 'progressbar'),
                                                    setStyle(['width' => '60%']),
                                                )
                                            )
                                        ),
                                        div
                                        (
                                            set('class', 'pt-5'),
                                            div
                                            (
                                                set('class', 'progress h-2'),
                                                div
                                                (
                                                    set('class', 'progress-bar'),
                                                    set('role', 'progressbar'),
                                                    setStyle(['width' => '30%']),
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                ),
                cell
                (
                    set('width', '32%'),
                    set('class', 'py-2 px-6'),
                    div
                    (
                        set('class', 'py-2'),
                        span('近期测试单')
                    ),
                )
            )
        );
    }
    return $tabItems;
};

$blockNavCode = 'nav-' . uniqid();
panel
(
    on::click('.nav-prev,.nav-next', 'switchNav'),
    set('class', 'qastatistic-block ' . ($longBlock ? 'block-long' : 'block-sm')),
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
