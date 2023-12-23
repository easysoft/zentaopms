<?php
declare(strict_types=1);
/**
* The product overview block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      LiuRuoGu <liuruogu@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$id = $block->module . '-' . $block->code . '-' . $block->id;

if($block->width == 1)
{
    blockPanel
    (
        set::bodyClass('row items-center text-center'),
        set::headingClass('border-0 pb-0'),
        cell
        (
            width('1/3'),
            a
            (
                setClass('text-3xl leading-none text-primary num'),
                hasPriv('product', 'all') ? set::href(createLink('product', 'all', 'browseType=all')) : null,
                $data->productCount
            ),
            div
            (
                setClass('text-gray text-sm mt-3'),
                $lang->block->productoverview->productCount
            )
        ),
        cell
        (
            width('1/3'),
            div
            (
                setClass('text-3xl leading-none text-center num'),
                $data->releaseCount
            ),
            div
            (
                setClass('text-gray text-sm mt-3'),
                $lang->block->productoverview->releaseCount
            )
        ),
        cell
        (
            width('1/3'),
            div
            (
                setClass('text-3xl leading-none text-important num'),
                $data->milestoneCount
            ),
            div
            (
                setClass('text-gray text-sm mt-3'),
                $lang->block->productoverview->milestoneCount
            )
        )
    );
}

if($block->width == 3)
{
    $suffix = ($app->clientLang == 'zh-cn' || $app->clientLang == 'zh-tw') ? $lang->year : '';

    $items = array();
    foreach($years as $year)
    {
        $url = createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("year={$year}"));
        $items[] = array('text' => $year . $suffix, 'url' => $url, 'data-load' => 'target', 'data-selector' => "#{$id}-annual", 'data-partial' => true);
    }

    blockPanel
    (
        set::headingClass('hidden'),
        set::bodyClass('gradient row p-1 pt-3'),
        set::bodyProps(array('style' => array('background-image' => 'linear-gradient( 90deg, #ECF7FE 0%, #FFF 22%)'))),
        col
        (
            setID("{$id}-overview"),
            width('62.5%'),
            div
            (
                setClass('text-md font-bold mt-2 mb-3 ml-3'),
                $block->title
            ),
            row
            (
                setClass('mt-4 text-center'),
                col
                (
                    setClass('w-1/5 gap-2'),
                    div
                    (
                        setClass('text-3xl leading-none num'),
                        $data->productLineCount
                    ),
                    div($lang->block->productoverview->productLineCount)
                ),
                col
                (
                    setClass('w-1/5 gap-2'),
                    a
                    (
                        setClass('text-3xl leading-none text-primary num'),
                        hasPriv('product', 'all') ? set::href(createLink('product', 'all', 'browseType=all')) : null,
                        $data->productCount
                    ),
                    div($lang->block->productoverview->productCount)
                ),
                col
                (
                    setClass('w-1/5 gap-2 border-l'),
                    div
                    (
                        setClass('text-3xl leading-none num'),
                        $data->unfinishedPlanCount
                    ),
                    div($lang->block->productoverview->unfinishedPlanCount)
                ),
                col
                (
                    setClass('w-1/5 gap-2'),
                    div
                    (
                        setClass('text-3xl leading-none num'),
                        $data->unclosedStoryCount
                    ),
                    div($lang->block->productoverview->unclosedStoryCount)
                ),
                col
                (
                    setClass('w-1/5 gap-2 border-r'),
                    div
                    (
                        setClass('text-3xl leading-none num'),
                        $data->activeBugCount
                    ),
                    div($lang->block->productoverview->activeBugCount)
                )
            )
        ),
        col
        (
            setID("{$id}-annual"),
            setClass('flex-auto'),
            row
            (
                setClass('text-sm font-bold mt-1 mb-3 ml-3 items-center'),
                span($lang->block->productoverview->yearFinished),
                dropdown
                (
                    btn
                    (
                        set::type('ghost'),
                        set::size('sm'),
                        setClass('text-gray ml-4'),
                        $currentYear . $suffix
                    ),
                    set::placement('bottom-end'),
                    set::menu(array('style' => array('minWidth' => 70, 'width' => 70))),
                    set::items($items)
                )
            ),
            row
            (
                setClass('mt-4 text-center'),
                col
                (
                    setClass('w-1/3 gap-2'),
                    div
                    (
                        setClass('text-3xl leading-none num'),
                        $data->finishedReleaseCount['year']
                    ),
                    div
                    (
                        $lang->block->productoverview->finishedReleaseCount,
                        !empty($data->finishedReleaseCount['week']) ? div
                        (
                            setClass('text-center'),
                            $lang->block->productoverview->thisWeek,
                            span
                            (
                                setClass('text-warning ml-1 num'),
                                '+' . $data->finishedReleaseCount['week']
                            )
                        ) : null
                    )
                ),
                col
                (
                    setClass('w-1/3 gap-2'),
                    div
                    (
                        setClass('text-3xl leading-none num'),
                        $data->finishedStoryCount['year']
                    ),
                    div
                    (
                        $lang->block->productoverview->finishedStoryCount,
                        !empty($data->finishedStoryCount['week']) ? div
                        (
                            $lang->block->productoverview->thisWeek,
                            span
                            (
                                setClass('text-warning ml-1 num'),
                                '+' . $data->finishedStoryCount['week']
                            )
                        ) : null
                    )
                ),
                col
                (
                    setClass('w-1/3 gap-2'),
                    div
                    (
                        setClass('text-3xl leading-none num'),
                        $data->finishedStoryPoint['year']
                    ),
                    div
                    (
                        $lang->block->productoverview->finishedStoryPoint,
                        span
                        (
                            setClass('text-gray'),
                            '(' . $config->block->storyUnitList[$config->custom->hourPoint] . ')'
                        ),
                        !empty($data->finishedStoryPoint['week']) ? div
                        (
                            $lang->block->productoverview->thisWeek,
                            span
                            (
                                setClass('text-warning ml-1 num'),
                                '+' . $data->finishedStoryPoint['week']
                            )
                        ) : null
                    )
                )
            )
        )
    );
}

render();
