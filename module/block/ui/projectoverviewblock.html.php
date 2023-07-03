<?php
declare(strict_types=1);
/**
* The project overview block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Gang Liu <liugang@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

panel
(
    setClass('projectoverview-block'),
    set::title($block->title),
    set::bodyClass('flex block-base p-0'),
    div
    (
        setClass('flex w-1/2'),
        col
        (
            setClass('justify-center w-1/2'),
            a
            (
                setClass('text-2xl text-center text-primary font-bold leading-relaxed border-r'),
                hasPriv('project', 'browse') ? set::href($this->createLink('project', 'browse', 'programID=0&browseType=all')) : null,
                $projectStats['total']
            ),
            span
            (
                setClass('text-center'),
                $lang->block->projectoveview->totalProject
            )
        ),
        col
        (
            setClass('justify-center w-1/2'),
            span
            (
                setClass('text-2xl text-center font-bold leading-relaxed'),
                $projectStats['thisYear']['count']
            ),
            span
            (
                setClass('text-center'),
                $lang->block->projectoverview->thisYear
            )
        )
    ),
    div
    (
        setClass('flex justify-center w-1/2'),
        col
        (
            span
            (
                setClass('text-center mb-2'),
                $lang->block->projectoverview->lastThreeYear
            ),
            div
            (
                setClass('border-b'),
                h::ul
                (
                    setClass('flex justify-around items-end w-full h-16'),
                    h::li
                    (
                        setStyle(array('display' => 'contents')),
                        span
                        (
                            setClass('block w-2 primary'),
                            set::title($projectStats['lastTwoYear']['count']),
                            setStyle(array('height' => $projectStats['lastTwoYear']['rate'])),
                        )
                    ),
                    h::li
                    (
                        setStyle(array('display' => 'contents')),
                        span
                        (
                            setClass('block w-2 primary'),
                            set::title($projectStats['lastYear']['count']),
                            setStyle(array('height' => $projectStats['lastYear']['rate'])),
                        )
                    ),
                    h::li
                    (
                        setStyle(array('display' => 'contents')),
                        span
                        (
                            setClass('block w-2 primary'),
                            set::title($projectStats['thisYear']['count']),
                            setStyle(array('height' => $projectStats['thisYear']['rate'])),
                        )
                    )
                )
            ),
            div
            (
                setClass('flex justify-around mt-1.5'),
                span
                (
                    setClass('text-center'),
                    $projectStats['lastTwoYear']['year']
                ),
                span
                (
                    setClass('text-center'),
                    $projectStats['lastYear']['year']
                ),
                span
                (
                    setClass('text-center'),
                    $projectStats['thisYear']['year']
                )
            )
        )
    )
);

render();
