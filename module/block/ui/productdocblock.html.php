<?php
declare(strict_types=1);
/**
* The productdoc block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

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
 * @param  array  $docGroup
 * @param  string $blockNavID
 * @param  bool   $longBlock
 * @return array
 */
$getProductInfo = function(array $products, array $docGroup, string $blockNavID, bool $longBlock): array
{
    global $lang, $config;
    $tabItems = array();
    $selected = key($products);
    foreach($products as $product)
    {
        $tabItems[] = div
        (
            set('class', 'tab-pane h-full' . ($product->id == $selected ? ' active' : '')),
            set('id', "tab3{$blockNavID}Content{$product->id}"),
            dtable
            (
                set::height(318),
                set::bordered(false),
                set::horzScrollbarPos('inside'),
                set::cols(array_values($config->block->doc->dtable->fieldList)),
                set::data(array_values($docGroup[$product->id])),
                set::userMap($users),
            )
        );
    }
    return $tabItems;
};

$blockNavCode = 'nav-' . uniqid();
panel
(
    set('id', "productdoc-block-{$block->id}"),
    on::click('.nav-prev,.nav-next', 'switchNav'),
    set('class', 'productdoc-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set('headingClass', 'border-b'),
    to::heading
    (
        div
        (
            set('class', 'panel-title'),
            span(span($block->title)),
            dropdown
            (
                a
                (
                    setClass('text-gray ml-4'),
                    $type == 'involved' ? $lang->product->involved : $lang->product->all,
                    span(setClass('caret align-middle ml-1'))
                ),
                set::items(array(
                    array('text' => $lang->product->involved, 'url' => createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("type=involved")), 'data-load' => 'target', 'data-selector' => "#productdoc-block-{$block->id}", 'data-partial' => true),
                    array('text' => $lang->product->all, 'url' => createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("type=all")), 'data-load' => 'target', 'data-selector' => "#productdoc-block-{$block->id}", 'data-partial' => true))
                )
            ),
        )
    ),
    to::headingActions
    (
        a
        (
            set('class', 'text-gray'),
            set('href', createLink('doc', 'productspace')),
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
            $getProductInfo($products, $docGroup, $blockNavCode, $longBlock)
        )
    )
);
render();
