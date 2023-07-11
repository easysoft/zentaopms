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
    panel
    (
        setID($id),
        setClass('product-overview-block'),
        set::title($block->title),
        set::titleClass('text-md'),
        set::bodyClass('flex p-0'),
        div
        (
            setClass('card col w-1/3 mt-6'),
            a
            (
                setClass('text-3xl text-center text-primary'),
                hasPriv('product', 'all') ? set::href(createLink('product', 'all', 'browseType=all')) : null,
                $data->productCount
            ),
            span
            (
                setClass('text-gray text-sm text-center mt-3'),
                $lang->block->productoverview->productCount
            )
        ),
        div
        (
            setClass('card col w-1/3 mt-6'),
            span
            (
                setClass('text-3xl text-center'),
                $data->releaseCount
            ),
            span
            (
                setClass('text-gray text-sm text-center mt-3'),
                $lang->block->productoverview->releaseCount
            )
        ),
        div
        (
            setClass('card col w-1/3 mt-6'),
            span
            (
                setClass('text-3xl text-center text-important'),
                $data->milestoneCount
            ),
            span
            (
                setClass('text-gray text-sm text-center mt-3'),
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
        $items[] = array('text' => $year . $suffix, 'data-on' => 'click', 'data-call' => "loadPage('{$url}', '#{$id}')");
    }

    panel
    (
        setID($id),
        setClass('product-overview-block'),
        set::title($block->title),
        set::titleClass('text-md'),
        set::bodyClass('gradient flex p-0'),
        col
        (
            setStyle(array('width' => '62.5%')),
            div
            (
                setClass('text-sm font-bold mt-2 ml-4'),
                $lang->block->productoverview->overview
            ),
            div
            (
                setClass('flex mt-4'),
                div
                (
                    setClass('cards flex w-2/5'),
                    div
                    (
                        setClass('card col w-1/2'),
                        span
                        (
                            setClass('text-3xl text-center'),
                            $data->productLineCount
                        ),
                        span
                        (
                            setClass('text-center'),
                            $lang->block->productoverview->productLineCount
                        )
                    ),
                    div
                    (
                        setClass('card col w-1/2'),
                        a
                        (
                            setClass('text-3xl text-center text-primary'),
                            hasPriv('product', 'all') ? set::href(createLink('product', 'all', 'browseType=all')) : null,
                            $data->productCount
                        ),
                        span
                        (
                            setClass('text-center'),
                            $lang->block->productoverview->productCount
                        )
                    )
                ),
                div
                (
                    setClass('cards flex w-3/5'),
                    div
                    (
                        setClass('card col w-1/3'),
                        span
                        (
                            setClass('text-3xl text-center border-l'),
                            $data->unfinishedPlanCount
                        ),
                        span
                        (
                            setClass('text-center border-l'),
                            $lang->block->productoverview->unfinishedPlanCount
                        )
                    ),
                    div
                    (
                        setClass('card col w-1/3'),
                        span
                        (
                            setClass('text-3xl text-center'),
                            $data->unclosedStoryCount
                        ),
                        span
                        (
                            setClass('text-center'),
                            $lang->block->productoverview->unclosedStoryCount
                        )
                    ),
                    div
                    (
                        setClass('card col w-1/3'),
                        span
                        (
                            setClass('text-3xl text-center'),
                            $data->activeBugCount
                        ),
                        span
                        (
                            setClass('text-center'),
                            $lang->block->productoverview->activeBugCount
                        )
                    )
                )
            )
        ),
        col
        (
            setStyle(array('width' => '37.5%')),
            div
            (
                setClass('text-sm font-bold mt-2 ml-4 flex'),
                span($lang->block->productoverview->yearFinished),
                dropdown
                (
                    a
                    (
                        setClass('text-gray ml-4'),
                        $currentYear . $suffix,
                        span(setClass('caret align-middle ml-1'))
                    ),
                    set::placement('bottom-end'),
                    set::menu(array('style' => array('minWidth' => 70, 'width' => 70))),
                    set::items($items)
                )
            ),
            div
            (
                setClass('cards flex mt-4'),
                div
                (
                    setClass('card col w-1/3'),
                    span
                    (
                        setClass('text-3xl text-center border-l'),
                        $data->finishedReleaseCount['year']
                    ),
                    span
                    (
                        setClass('text-center border-l'),
                        $lang->block->productoverview->finishedReleaseCount
                    ),
                    !empty($data->finishedReleaseCount['week']) ? span
                    (
                        setClass('text-center'),
                        $lang->block->productoverview->thisWeek,
                        span
                        (
                            setClass('text-warning ml-1'),
                            '+' . $data->finishedReleaseCount['week']
                        )
                    ) : null
                ),
                div
                (
                    setClass('card col w-1/3'),
                    span
                    (
                        setClass('text-3xl text-center'),
                        $data->finishedStoryCount['year']
                    ),
                    span
                    (
                        setClass('text-center'),
                        $lang->block->productoverview->finishedStoryCount
                    ),
                    !empty($data->finishedStoryCount['week']) ? span
                    (
                        setClass('text-center'),
                        $lang->block->productoverview->thisWeek,
                        span
                        (
                            setClass('text-warning ml-1'),
                            '+' . $data->finishedStoryCount['week']
                        )
                    ) : null
                ),
                div
                (
                    setClass('card col w-1/3'),
                    span
                    (
                        setClass('text-3xl text-center'),
                        $data->finishedStoryPoint['year']
                    ),
                    span
                    (
                        setClass('text-center'),
                        $lang->block->productoverview->finishedStoryPoint,
                        span
                        (
                            setClass('text-gray'),
                            '(SP)'
                        )
                    ),
                    !empty($data->finishedStoryPoint['week']) ? span
                    (
                        setClass('text-center'),
                        $lang->block->productoverview->thisWeek,
                        span
                        (
                            setClass('text-warning ml-1'),
                            '+' . $data->finishedStoryPoint['week']
                        )
                    ) : null
                )
            )
        )
    );
}

render();
