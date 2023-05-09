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
                set('class', 'ellipsis'),
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
            set('class', 'tab-pane' . ($product->id == $selected ? ' active' : '')),
            set('id', "tab3{$blockNavID}Content{$product->id}"),
            div
            (
                set('class', 'flex'),
                cell
                (
                    set('width', '68%'),
                    div
                    (
                        set('class', 'flex'),
                        cell
                        (
                            set('width', '45%'),
                            set('class', 'px-3 py-2'),
                            div
                            (
                                set('class', 'mx-6 my-4 bg-primary aspect-square')
                            ),
                            div
                            (
                                set('class', 'flex'),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div(span('8073')),
                                    div(span('BUG总数'))
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div(span('6549')),
                                    div(span('已关闭'))
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div(span('1542')),
                                    div(span('未关闭'))
                                )
                            )
                        ),
                        cell
                        (
                            set('width', '55%'),
                            set('class', 'py-2 border-r'),
                            div
                            (
                                set('class', 'py-2'),
                                span('未完成的需求统计')
                            ),
                            div
                            (
                                set('class', 'py-1'),
                                span('本周完成 12 | '),
                                span('本周新增 35')
                            ),
                            div
                            (
                                set('class', 'mr-4 bg-primary aspect-video'),
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
                        span('产品最新推进')
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
    set('class', 'productstatistic-block'),
    div
    (
        set('class', 'flex'),
        cell
        (
            set('width', '25%'),
            set('class', 'of-hidden bg-secondary-pale'),
            ul
            (
                set('class', 'nav nav-tabs nav-stacked'),
                getProductTabs($products, $blockNavCode)
            ),
        ),
        cell
        (
            set('class', 'tab-content'),
            set('width', '75%'),
            getProductInfo($products, $blockNavCode)
        )
    )
);

render();
