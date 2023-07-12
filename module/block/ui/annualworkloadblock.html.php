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
foreach($doneStoryEstimate as $projectID => $storyEstimate)
{
    $doneStoryEstimateRows[] = cell
    (
        setClass('flex py-1.5 pr-4 text-sm'),
        cell
        (
            setClass('hidden-nowrap mr-1 text-right'),
            set::width('50%'),
            span
            (
                set::title(zget($projectPairs, $projectID)),
                zget($projectPairs, $projectID),
            ),
        ),
        cell
        (
            set::width('50%'),
            setClass('flex items-center'),
            div
            (
                width("calc(calc(100% - {$spanWidth}) * " . ($storyEstimate / $maxStoryEstimate) . ')'),
                setClass('progress-bar'),
                set::style(array('height' => '0.75rem')),
                set('role', 'progressbar'),
            ),
            span
            (
                setClass('pl-0.5'),
                $storyEstimate
            ),
        ),
    );
}

$spanWidth          = (strlen((string)($maxStoryCount)) * 0.425 + 0.125) . 'rem';
$doneStoryCountRows = array();
foreach($doneStoryCount as $projectID => $storyCount)
{
    $doneStoryCountRows[] = cell
    (
        setClass('flex py-1.5 pr-4 text-sm'),
        cell
        (
            setClass('hidden-nowrap mr-1 text-right'),
            set::width('50%'),
            span
            (
                zget($projectPairs, $projectID),
            ),
        ),
        cell
        (
            set::width('50%'),
            setClass('flex items-center'),
            div
            (
                width("calc(calc(100% - {$spanWidth}) * " . ($storyCount / $maxStoryCount) . ')'),
                setClass('progress-bar'),
                set::style(array('height' => '0.75rem')),
                set('role', 'progressbar'),
            ),
            span
            (
                setClass('pl-0.5'),
                $storyCount
            ),
        ),
    );
}

$spanWidth            = (strlen((string)($maxBugCount)) * 0.425 + 0.125) . 'rem';
$resolvedBugCountRows = array();
foreach($resolvedBugCount as $projectID => $bugCount)
{
    $resolvedBugCountRows[] = cell
    (
        setClass('flex py-1.5 pr-4 text-sm'),
        cell
        (
            setClass('hidden-nowrap mr-1 text-right'),
            set::width('50%'),
            span
            (
                zget($projectPairs, $projectID),
            ),
        ),
        cell
        (
            set::width('50%'),
            setClass('flex items-center'),
            div
            (
                width("calc(calc(100% - {$spanWidth}) * " . ($bugCount / $maxBugCount) . ')'),
                setClass('progress-bar'),
                set::style(array('height' => '0.75rem')),
                set('role', 'progressbar'),
            ),
            span
            (
                setClass('pl-0.5'),
                $bugCount
            ),
        ),
    );
}

panel
(
    set('headingClass', 'border-b'),
    set::title($block->title),
    set::bodyClass('px-0'),
    div
    (
        setClass('flex h-full w-full gap-x-1' . ($longBlock ? ' flex-nowrap' : ' flex-wrap')),
        cell
        (
            setClass('flex flex-wrap px-4 ' . ($longBlock ? 'py-2' : 'py-1 w-full mb-2')),
            set::width($longBlock ? '1/3' : '100%'),
            cell
            (
                setClass('w-full font-bold'),
                icon('card-archive mr-1 mb-2'),
                span
                (
                    $lang->block->annualworkload->doneStoryEstimate
                ),
            ),
            cell
            (
                setClass('w-full overflow-y-auto' . ($longBlock ? ' h-64' : ' h-48')),
                $doneStoryEstimateRows,
            ),
        ),
        cell
        (
            setClass('flex flex-wrap px-4 ' . ($longBlock ? 'py-2' : 'py-3 w-full my-2')),
            !$longBlock ? set::style(array('background-color' => 'var(--color-slate-100)')) : '',
            set::width($longBlock ? '1/3' : '100%'),
            cell
            (
                setClass('w-full font-bold'),
                icon('sub-review mr-1 mb-2'),
                span
                (
                    $lang->block->annualworkload->doneStoryCount
                ),
            ),
            cell
            (
                setClass('w-full overflow-y-auto' . ($longBlock ? ' h-64' : ' h-48')),
                $doneStoryCountRows,
            ),
        ),
        cell
        (
            setClass('flex flex-wrap px-4 ' . ($longBlock ? 'py-2' : 'w-full my-2')),
            set::width($longBlock ? '1/3' : '100%'),
            cell
            (
                setClass('w-full font-bold'),
                icon('bug mr-1 mb-2'),
                span
                (
                    $lang->block->annualworkload->resolvedBugCount
                ),
            ),
            cell
            (
                setClass('w-full overflow-y-auto' . ($longBlock ? ' h-64' : ' h-48')),
                $resolvedBugCountRows,
            ),
        ),
    )
);

render();
