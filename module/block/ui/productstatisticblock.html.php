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
function getProductTabs(array $products, string $blockNavCode, bool $longBlock): array
{
    $navTabs  = array();
    $selected = key($products);
    $navTabs[] = li
    (
        set('class', 'nav-item of-hidden nav-prev rounded-full bg-white shadow-md h-6 w-6'),
        a(icon(set('size', '24'), 'angle-left'))
    );
    foreach($products as $product)
    {
        $navTabs[] = li
        (
            set('class', 'nav-item nav-switch w-full' . ($product->id == $selected ? ' active' : '')),
            a
            (
                set('class', 'ellipsis text-dark'),
                $longBlock ? set('data-toggle', 'tab') : null,
                set('href', $longBlock ? "#tab3{$blockNavCode}Content{$product->id}" : helper::createLink('product', 'browse', "productID=$product->id")),
                $product->name

            ),
            !$longBlock ? a
            (
                set('class', 'hidden'),
                set('data-toggle', 'tab'),
                set('href', "#tab3{$blockNavCode}Content{$product->id}"),
            ) : null,
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
    $navTabs[] = li
    (
        set('class', 'nav-item of-hidden nav-next rounded-full bg-white shadow-md h-6 w-6'),
        a(icon(set('size', '24'), 'angle-right'))
    );
    return $navTabs;
}

/**
 * 获取区块右侧显示的产品信息。
 * Get product statistical information.
 *
 * @param  array  $products
 * @param  string $blockNavID
 * @param  bool   $longBlock
 * @return array
 */
function getProductInfo(array $products, string $blockNavID, bool $longBlock): array
{
    global $lang;

    $selected = key($products);
    $tabItems = array();
    foreach($products as $product)
    {
        $stories      = $product->stories;
        $monthStories = $product->monthStories;
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
                    set('width', '70%'),
                    div
                    (
                        set('class', 'flex h-full ' . ($longBlock ? '' : 'col')),
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
                                set('class', 'flex h-full story-num w-44'),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div
                                    (
                                        common::hasPriv('product', 'browse') ? a
                                        (
                                            set('href', helper::createLink('product', 'browse', "productID={$product->id}&branch=all&browseType=allStory-0-story")),
                                            set('class', 'text-black'),
                                            $stories ? $stories['total'] : 0
                                        ) : span
                                        (
                                            set('class', 'text-black'),
                                            $stories ? $stories['total'] : 0
                                        )
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            $lang->block->productstatistic->totalStory
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div
                                    (
                                        common::hasPriv('product', 'browse') ? a
                                        (
                                            set('href', helper::createLink('product', 'browse', "productID={$product->id}&branch=all&browseType=closedstory-0-story")),
                                            set('class', 'text-black'),
                                            $stories ? $stories['closed'] : 0
                                        ) : span
                                        (
                                            set('class', 'text-black'),
                                            $stories ? $stories['closed'] : 0
                                        )
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            $lang->block->productstatistic->closed
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div
                                    (
                                        common::hasPriv('product', 'browse') ? a
                                        (
                                            set('href', helper::createLink('product', 'browse', "productID={$product->id}&branch=all&browseType=unclosed-0-story")),
                                            set('class', 'text-black'),
                                            $stories ? $stories['notClosed'] : 0
                                        ) : span
                                        (
                                            set('class', 'text-black'),
                                            $stories ? $stories['notClosed'] : 0
                                        )
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            $lang->block->productstatistic->notClosed
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
                                    set('class', 'px-4 pb-2'),
                                    $lang->block->productstatistic->storyStatistics
                                ),
                                div
                                (
                                    set('class', 'px-4'),
                                    span
                                    (
                                        set('class', 'border-r pr-2 text-sm text-gray'),
                                        html(sprintf($lang->block->productstatistic->monthDone, !empty($monthStories[date('Y-m')]) ? $monthStories[date('Y-m')]->done : 0))
                                    ),
                                    span
                                    (
                                        set('class', 'pl-2 text-sm text-gray'),
                                        html(sprintf($lang->block->productstatistic->monthOpened, !empty($monthStories[date('Y-m')]) ? $monthStories[date('Y-m')]->opened : 0))
                                    )
                                ),
                                div
                                (
                                    set('class', 'px-4 py-2'),
                                    div
                                    (
                                        set('class', 'bg-primary h-44 w-full'),
                                    )
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
                        set('class', 'pb-4'),
                        div(span(set('class', 'text-sm text-gray'), $lang->block->productstatistic->newPlan)),
                        div
                        (
                            set('class', 'py-1'),
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
                        set('class', 'pb-4'),
                        div(span(set('class', 'text-sm text-gray'), $lang->block->productstatistic->newExecution)),
                        div
                        (
                            set('class', 'py-1'),
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
                        div(span(set('class', 'text-sm text-gray'), $lang->block->productstatistic->newRelease)),
                        div
                        (
                            set('class', 'py-1'),
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
}

$blockNavCode = 'nav-' . uniqid();
div
(
    on::click('.nav-prev,.nav-next', 'switchNav'),
    set('class', 'productstatistic-block of-hidden ' . ($longBlock ? 'block-long' : 'block-sm')),
    div
    (
        set('class', "flex h-full " . ($longBlock ? '' : 'col')),
        cell
        (
            set('width', '22%'),
            set('class', $longBlock ? 'bg-secondary-pale' : ''),
            ul
            (
                set('class', 'nav nav-tabs ' .  ($longBlock ? 'nav-stacked h-full of-y-auto of-x-hidden' : 'pt-4 px-4')),
                getProductTabs($products, $blockNavCode, $longBlock)
            ),
        ),
        cell
        (
            set('class', 'tab-content'),
            set('width', '78%'),
            getProductInfo($products, $blockNavCode, $longBlock)
        )
    )
);

render();
