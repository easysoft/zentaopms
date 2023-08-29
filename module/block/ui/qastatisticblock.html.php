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
        $waitTesttasks = array();
        if(!empty($product->waitTesttasks))
        {
            foreach($product->waitTesttasks as $waitTesttask)
            {
                $waitTesttasks[] = div(set('class', 'py-1'), common::hasPriv('testtask', 'cases') ? a(set('href', createLink('testtask', 'cases', "taskID={$waitTesttask->id}")), $waitTesttask->name) : span($waitTesttask->name));
                if(count($waitTesttasks) >= 2) break;
            }
        }

        $doingTesttasks = array();
        if(!empty($product->doingTesttasks))
        {
            foreach($product->doingTesttasks as $doingTesttask)
            {
                $doingTesttasks[] = div(set('class', 'py-1'), common::hasPriv('testtask', 'cases') ? a(set('href', createLink('testtask', 'cases', "taskID={$doingTesttask->id}")), $doingTesttask->name) : span($doingTesttask->name));
                if(count($doingTesttasks) >= 2) break;
            }
        }

        $progressMax = max($product->addYesterday, $product->addToday, $product->resolvedYesterday, $product->resolvedToday, $product->closedYesterday, $product->closedToday);
        $progress      = array();
        $progressBlcok = array();
        $progressLabel = array();
        foreach(array('addYesterday', 'addToday', 'resolvedYesterday', 'resolvedToday', 'closedYesterday', 'closedToday') as $key => $field)
        {
            $progressLabel[] = div(set('class', ($key % 2 === 0 ? 'py-1' : 'text-gray pt-1 pb-3')), span($lang->block->qastatistic->{$field}), span(set('class', 'ml-1'), $product->{$field}));
            $progress[]      = div
            (
                set('class', $key % 2 === 0 ? 'pt-2' : 'py-5'),
                div
                (
                    set('class', 'progress h-2'),
                    div
                    (
                        set('class', 'progress-bar'),
                        set('role', 'progressbar'),
                        setStyle(array('width' => (($progressMax ? $product->{$field} / $progressMax : 0) * 100) . '%', 'background' => $key % 2 === 0 ? 'var(--color-secondary-200)' : 'var(--color-primary-300)')),
                    )
                )
            );
        }

        $progressBlcok = array();
        $progressBlcok[] = div
        (
            set('class', 'flex py-1 pr-4 ' . ($waitTesttasks || $doingTesttasks ? 'border-r' : '')),
            cell
            (
                $progressLabel
            ),
            cell
            (
                set('class', 'flex-1 px-3'),
                $progress
            )
        );

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
                                                'type'      => 'pie',
                                                'radius'    => array('80%', '90%'),
                                                'itemStyle' => array('borderRadius' => '40'),
                                                'label'     => array('show' => false),
                                                'data'      => array($product->closedBugRate, 100 - $product->closedBugRate)
                                            )
                                        )
                                    )
                                )->size('100%', 120),
                                div
                                (
                                    set::className('pie-chart-title text-center'),
                                    div(span(set::className('text-2xl font-bold'), $product->closedBugRate . '%')),
                                    div
                                    (
                                        span
                                        (
                                            setClass('text-sm text-gray'),
                                            $lang->block->qastatistic->closedBugRate,
                                            icon
                                            (
                                                'help',
                                                toggle::tooltip(array('title' => '提示文本'))
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
                                        span(!empty($product->totalBug) ? $product->totalBug : 0)
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
                                        span(!empty($product->closedBug) ? $product->closedBug : 0)
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            $lang->bug->statusList['closed']
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div
                                    (
                                        span(!empty($product->activatedBug) ? $product->activatedBug : 0)
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            $lang->bug->statusList['active']
                                        )
                                    )
                                )
                            )
                        ),
                        cell
                        (
                            $longBlock ? set('width', '60%') : null,
                            set('class', 'py-4 ' . (!$longBlock ? 'px-4 flex' : '')),
                            cell
                            (
                                set('class', 'flex-1'),
                                div
                                (
                                    $longBlock ? set('class', 'pb-2') : null,
                                    $lang->block->qastatistic->bugStatistics
                                ),
                                $progressBlcok
                            ),
                            !$longBlock && ($doingTesttasks || $waitTesttasks) ? cell
                            (
                                set('width', '50%'),
                                set('class', 'px-4'),
                                div(span($lang->block->qastatistic->latestTesttask)),
                                $doingTesttasks ? div
                                (
                                    set('class', 'py-2'),
                                    div(set('class', 'text-sm pb-2'), $lang->testtask->statusList['doing']),
                                    $doingTesttasks
                                ) : null,
                                $waitTesttasks ? div
                                (
                                    set('class', 'py-2'),
                                    div(set('class', 'text-sm pb-2'), $lang->testtask->statusList['wait']),
                                    $waitTesttasks
                                ) : null
                            ) : null
                        )
                    )
                ),
                $longBlock && ($doingTesttasks || $waitTesttasks) ? cell
                (
                    set('width', '30%'),
                    set('class', 'py-2 px-6'),
                    div
                    (
                        set('class', 'py-2'),
                        span($lang->block->qastatistic->latestTesttask)
                    ),
                    $doingTesttasks ? div
                    (
                        set('class', 'py-2'),
                        div(set('class', 'text-sm pb-2'), $lang->testtask->statusList['doing']),
                        $doingTesttasks
                    ) : null,
                    $waitTesttasks ? div
                    (
                        set('class', 'py-2'),
                        div(set('class', 'text-sm pb-2'), $lang->testtask->statusList['wait']),
                        $waitTesttasks
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
    set('id', "qastatistic-block-{$block->id}"),
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
