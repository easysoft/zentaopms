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

$active  = isset($params['active']) ? $params['active'] : key($products); // 当前产品 ID。
$product = null;        // 当前产品。 Current active product.
$items   = array();     // 产品导航列表。 Product nav list.

/* 生成左侧菜单项列表。 */
foreach($products as $productItem)
{
    $projectID = isset($params['projectID']) ? $params['projectID'] : 0;
    $params    = helper::safe64Encode("module={$block->module}&projectID={$projectID}&active={$productItem->id}");
    $items[]   = array
    (
        'id'        => $productItem->id,
        'text'      => $productItem->name,
        'url'       => createLink('bug', 'browse', "productID=$productItem->id"),
        'activeUrl' => createLink('block', 'printBlock', "blockID=$block->id&params=$params")
    );
    if($productItem->id == $active) $product = $productItem;
}

/**
 * 构建进度条。
 *
 * @param object $product   产品。
 * @param bool   $longBlock 是否为长块。
 */
$buildProgressBars = function(object $product, bool $longBlock): wg
{
    global $lang;
    $progressMax = max($product->addYesterday, $product->addToday, $product->resolvedYesterday, $product->resolvedToday, $product->closedYesterday, $product->closedToday);
    $labels = array();
    $bars   = array();
    $fields = array('addYesterday', 'addToday', 'resolvedYesterday', 'resolvedToday', 'closedYesterday', 'closedToday');
    foreach($fields as $index => $field)
    {
        $isEven = $index % 2 === 0;
        $labels[] = row
        (
            setClass('clip items-center', $isEven ? 'mt-3' : 'text-gray', $longBlock ? 'h-6' : 'h-5'),
            span($lang->block->qastatistic->{$field}),
            span
            (
                setClass('ml-1.5 inline-block text-left', $isEven ? 'font-bold' : ''),
                setStyle('min-width', '1.5em'),
                $product->{$field}
            )
        );
        $bars[] = row
        (
            setClass('items-center ml-1 border-l', $isEven ? 'mt-3' : '', $longBlock ? 'h-6' : 'h-5'),
            progressBar
            (
                setClass('progress flex-auto'),
                set::height(8),
                set::percent(($progressMax ? $product->{$field} / $progressMax : 0) * 100),
                set::color($isEven ? 'var(--color-secondary-200)' : 'var(--color-primary-300)'),
                set::background('rgba(0,0,0,0.02)')
            )
        );
    }

    return row
    (
        cell
        (
            setClass('text-right flex-none'),
            $labels
        ),
        cell
        (
            setClass('flex-auto'),
            $bars
        )
    );
};

/**
 * 构建测试任务列表。
 *
 * @param object $product   产品。
 * @param bool   $longBlock 是否为长块。
 */
$buildTesttasks = function(object $product, bool $longBlock): wg|null
{
    global $lang;
    $waitTesttasks = array();
    if(!empty($product->waitTesttasks))
    {
        foreach($product->waitTesttasks as $waitTesttask)
        {
            $waitTesttasks[] = div
            (
                setClass('clip', $longBlock ? 'py-1' : 'py-0.5'),
                hasPriv('testtask', 'cases') ? a(set('href', createLink('testtask', 'cases', "taskID={$waitTesttask->id}")), $waitTesttask->name) : span($waitTesttask->name)
            );
            if(count($waitTesttasks) >= 2) break;
        }
    }

    $doingTesttasks = array();
    if(!empty($product->doingTesttasks))
    {
        foreach($product->doingTesttasks as $doingTesttask)
        {
            $doingTesttasks[] = div
            (
                setClass('clip', $longBlock ? 'py-1' : 'py-0.5'),
                common::hasPriv('testtask', 'cases') ? a(set('href', createLink('testtask', 'cases', "taskID={$doingTesttask->id}")), $doingTesttask->name) : span($doingTesttask->name)
            );
            if(count($doingTesttasks) >= 2) break;
        }
    }

    if(empty($waitTesttasks) && empty($doingTesttasks)) return null;

    return col
    (
        setClass('min-w-0 flex-1 gap-1.5 px-3 pt-2 border-l'),
        div($lang->block->qastatistic->latestTesttask),
        empty($doingTesttasks) ? null : div
        (
            setClass($longBlock ? 'py-2' : 'pt-2'),
            div(setClass('text-sm', $longBlock ? 'pb-2' : 'pb-1'), $lang->testtask->statusList['doing']),
            $doingTesttasks
        ),
        empty($waitTesttasks) ? null : div
        (
            setClass($longBlock ? 'py-2' : 'pt-2'),
            div(setClass('text-sm', $longBlock ? 'pb-2' : 'pb-1'), $lang->testtask->statusList['wait']),
            $waitTesttasks
        )
    );
};

$testTasksView = !empty($product) ? $buildTesttasks($product, $longBlock) : null;

statisticBlock
(
    set::block($block),
    set::active($active),
    set::items($items),
    div
    (
        setClass($longBlock ? 'row' : 'col gap-3', 'h-full overflow-hidden items-stretch px-2 py-3'),
        center
        (
            setClass('gap-4 px-5', $testTasksView ? 'flex-none' : 'flex-1'),
            progressCircle
            (
                set::percent($product->fixedBugRate),
                set::size(112),
                set::text(false),
                set::circleWidth(0.06),
                div(span(setClass('text-2xl font-bold'), $product->fixedBugRate), '%'),
                div
                (
                    setClass('row text-gray items-center gap-1'),
                    $lang->block->qastatistic->fixBugRate
                )
            ),
            row
            (
                setClass('justify-center items-center gap-4'),
                center
                (
                    div(span(!empty($product->totalBug) ? $product->totalBug : 0)),
                    div
                    (
                        setClass('text-sm text-gray'),
                        $lang->block->bugstatistic->effective
                    )
                ),
                center
                (
                    div(span(!empty($product->fixedBug) ? $product->fixedBug : 0)),
                    div
                    (
                        setClass('text-sm text-gray'),
                        $lang->block->bugstatistic->fixed
                    )
                ),
                center
                (
                    div(span(!empty($product->activatedBug) ? $product->activatedBug : 0)),
                    div
                    (
                        setClass('text-sm text-gray'),
                        $lang->bug->statusList['active']
                    )
                )
            )
        ),
        row
        (
            setClass($testTasksView ? 'flex-auto' : 'flex-1'),
            col
            (
                setClass('flex-1 gap-1.5 px-3 py-2'),
                div($lang->block->qastatistic->bugStatistics),
                !empty($product) ? $buildProgressBars($product, $longBlock) : null
            ),
            $testTasksView
        )
    )
);
