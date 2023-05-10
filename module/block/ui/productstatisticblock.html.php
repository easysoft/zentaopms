<?php
declare(strict_types=1);
/**
* The product statistic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wangyuting <wangyuting@easycorp.ltd>
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
 * @access public
 * @return array
 */
function getProductTabs($products, $blockNavCode): array
{
    $navTabs  = array();
    $selected = key($products);
    foreach($products as $product)
    {
        $navTabs[] = li
        (
            set('class', 'nav-item' . ($product->id == $selected ? ' active' : '')),
            a
            (
                set('class', 'ellipsis text-dark'),
                set('data-toggle', 'tab'),
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
                    'export'
                )
            )
        );
    }
    return $navTabs;
}

/**
 * 获取区块右侧显示的项目信息.
 * Get product statistical information.
 *
 * @param  object   $products
 * @param  string   $blockNavID
 * @access public
 * @return array
 */
function getProductInfo($products, $blockNavID): array
{
    global $lang;

    $selected = key($products);
    $tabItems = array();
    foreach($products as $product)
    {
        $tabItems[] = div
        (
            set('class', 'h-full' . ($product->id == $selected ? ' active' : '')),
            set('id', "tab3{$blockNavID}Content{$product->id}"),
            div
            (
                set('class', 'flex h-full'),
                cell
                (
                    set('class', 'flex-1'),
                    set('width', '70%'),
                    div
                    (
                        set('class', 'flex h-full'),
                        cell
                        (
                            set('width', '40%'),
                            set('class', 'p-4'),
                            div
                            (
                                set('class', 'py-6'),
                                div(set('class', 'bg-primary aspect-square w-28 chart'))
                            ),
                            div
                            (
                                set('class', 'flex'),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div(span('8073')),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            'BUG总数'
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div(span('6549')),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            '已关闭'
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div(span('1542')),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            '未关闭'
                                        )
                                    )
                                )
                            )
                        ),
                        cell
                        (
                            set('width', '60%'),
                            set('class', 'py-4'),
                            div
                            (
                                set('class', 'border-r'),
                                div
                                (
                                    set('class', 'px-2 pb-2'),
                                    span('未完成的需求统计')
                                ),
                                div
                                (
                                    set('class', 'px-2'),
                                    span
                                    (
                                        set('class', 'border-r pr-2 text-sm text-gray'),
                                        html('本周完成 <span class="text-success font-bold">12</span>')
                                    ),
                                    span
                                    (
                                        set('class', 'pl-2 text-sm text-gray'),
                                        html('本周新增 <span class="text-black font-bold">35</span>')
                                    )
                                ),
                                div
                                (
                                    set('class', 'pr-4 py-2'),
                                    div
                                    (
                                        set('class', 'bg-primary h-44 w-full'),
                                    )
                                )
                            )
                        )
                    )
                ),
                cell
                (
                    set('width', '189'),
                    set('class', 'p-4'),
                    div
                    (
                        set('class', 'pb-2'),
                        span('产品最新推进')
                    ),
                    div
                    (
                        set('class', 'py-2'),
                        div(span(set('class', 'text-sm'), '最新计划')),
                        div(set('class', 'py-1'), a('18.8.stabe'), span(set('class', 'label light-pale rounded-xl ml-2 px-1'), '未开始'))
                    ),
                    div
                    (
                        set('class', 'py-2'),
                        div(span(set('class', 'text-sm'), '最新执行')),
                        div(set('class', 'py-1'), a('18.8.stabe'), span(set('class', 'label important-pale rounded-xl ml-2'), '进行中'))
                    ),
                    div
                    (
                        set('class', 'py-2'),
                        div(span(set('class', 'text-sm'), '最新发布')),
                        div(set('class', 'py-1'), a('18.8.stabe'), span(set('class', 'label success-pale rounded-xl ml-2'), '正常'))
                    ),
                )
            )
        );
    }
    return $tabItems;
}

$blockNavCode = 'nav-' . uniqid();
div
(
    set('class', 'productstatistic-block of-hidden ' . ($longBlock ? '' : 'block-sm')),
    div
    (
        set('class', "flex h-full" . ($longBlock ? '' : 'col')),
        cell
        (
            set('width', '22%'),
            set('class', 'of-hidden bg-secondary-pale'),
            ul
            (
                set('class', 'nav nav-tabs nav-stacked h-full of-y-auto of-x-hidden'),
                getProductTabs($products, $blockNavCode)
            ),
        ),
        cell
        (
            set('class', 'tab-content'),
            set('width', '78%'),
            getProductInfo($products, $blockNavCode)
        )
    )
);

render();
