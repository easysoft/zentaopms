<?php
declare(strict_types=1);
/**
* The annualworkload block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$spanWidth             = (strlen((string)($maxStoryEstimate)) * 0.425 + 0.125) . 'rem';
$doneStoryEstimateRows = array();
foreach($doneStoryEstimate as $productID => $storyEstimate)
{
    $doneStoryEstimateRows[] = cell
    (
        setClass('flex py-1.5 pr-4 text-sm'),
        cell
        (
            setClass('ellipsis overflow-hidden whitespace-nowrap mr-1 text-right'),
            set::width('50%'),
            span
            (
                set::title(zget($products, $productID)),
                zget($products, $productID)
            )
        ),
        cell
        (
            set::width('50%'),
            setClass('flex items-center'),
            div
            (
                width("calc(calc(100% - {$spanWidth}) * " . ($maxStoryEstimate ? $storyEstimate / $maxStoryEstimate : 0) . ')'),
                setClass('progress progress-bar h-2'),
                set('role', 'progressbar'),
                setStyle(array('background' => 'var(--color-primary-300)'))
            ),
            span
            (
                setClass('pl-0.5'),
                $storyEstimate
            )
        )
    );
}

$spanWidth          = (strlen((string)($maxStoryCount)) * 0.425 + 0.125) . 'rem';
$doneStoryCountRows = array();
foreach($doneStoryCount as $productID => $storyCount)
{
    $doneStoryCountRows[] = cell
    (
        setClass('flex py-1.5 pr-4 text-sm'),
        cell
        (
            setClass('ellipsis overflow-hidden whitespace-nowrap mr-1 text-right'),
            set::width('50%'),
            span
            (
                zget($products, $productID),
            )
        ),
        cell
        (
            set::width('50%'),
            setClass('flex items-center'),
            div
            (
                width("calc(calc(100% - {$spanWidth}) * " . ($maxStoryCount ? $storyCount / $maxStoryCount : 0) . ')'),
                setClass('progress progress-bar h-2'),
                set('role', 'progressbar'),
                setStyle(array('background' => 'var(--color-primary-300)'))
            ),
            span
            (
                setClass('pl-0.5'),
                $storyCount
            )
        )
    );
}

$spanWidth            = (strlen((string)($maxBugCount)) * 0.425 + 0.125) . 'rem';
$resolvedBugCountRows = array();
foreach($resolvedBugCount as $productID => $bugCount)
{
    $resolvedBugCountRows[] = cell
    (
        setClass('flex py-1.5 pr-4 text-sm'),
        cell
        (
            setClass('ellipsis overflow-hidden whitespace-nowrap mr-1 text-right'),
            set::width('50%'),
            span(zget($products, $productID))
        ),
        cell
        (
            set::width('50%'),
            setClass('flex items-center'),
            div
            (
                width("calc(calc(100% - {$spanWidth}) * " . ($maxBugCount ? $bugCount / $maxBugCount : 0) . ')'),
                setClass('progress progress-bar h-2'),
                set('role', 'progressbar'),
                setStyle(array('background' => 'var(--color-primary-300)'))
            ),
            span
            (
                setClass('pl-0.5'),
                $bugCount
            )
        )
    );
}

blockPanel
(
    div
    (
        setClass('flex h-full w-full' . ($longBlock ? ' flex-nowrap' : ' flex-wrap')),
        cell
        (
            setClass('flex flex-wrap overflow-hidden px-4 ' . ($longBlock ? 'py-2' : 'py-1 w-full mb-2')),
            set::width($longBlock ? '1/3' : '100%'),
            cell
            (
                setClass('w-full font-bold'),
                icon('card-archive mr-1 mb-2'),
                span($lang->block->annualworkload->doneStoryEstimate)
            ),
            cell
            (
                setClass('w-full overflow-y-auto' . ($longBlock ? ' h-64' : ' h-48')),
                $doneStoryEstimateRows
            )
        ),
        cell
        (
            setClass('flex flex-wrap overflow-hidden px-4 ' . ($longBlock ? 'py-2' : 'py-3 w-full my-2 border-t')),
            set::width($longBlock ? '1/3' : '100%'),
            cell
            (
                setClass('w-full font-bold'),
                icon('sub-review mr-1 mb-2'),
                span($lang->block->annualworkload->doneStoryCount)
            ),
            cell
            (
                setClass('w-full overflow-y-auto' . ($longBlock ? ' h-64' : ' h-48')),
                $doneStoryCountRows
            )
        ),
        cell
        (
            setClass('flex flex-wrap overflow-hidden px-4 ' . ($longBlock ? 'py-2' : 'py-3 w-full my-2 border-t')),
            set::width($longBlock ? '1/3' : '100%'),
            cell
            (
                setClass('w-full font-bold'),
                icon('bug mr-1 mb-2'),
                span($lang->block->annualworkload->resolvedBugCount)
            ),
            cell
            (
                setClass('w-full overflow-y-auto' . ($longBlock ? ' h-64' : ' h-48')),
                $resolvedBugCountRows
            )
        )
    )
);

render();
