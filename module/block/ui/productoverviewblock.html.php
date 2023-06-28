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

panel
(
    set::title($block->title),
    set::bodyClass('pt-0'),
    div
    (
        setClass('flex block-base'),
        col
        (
            setClass('text-center w-1/3'),
            set::justify('center'),
            a
            (
                setClass('text-2xl font-bold leading-relaxed'),
                set::href($this->createLink('product', 'all', 'browseType=all')),
                $totalProductCount
            ),
            span
            (
                setClass('text-light'),
                $lang->block->productoverview->totalProductCount
            )
        ),
        col
        (
            setClass('text-center w-1/3'),
            set::justify('center'),
            span
            (
                setClass('text-2xl font-bold leading-relaxed'),
                $productReleasedThisYear
            ),
            span
            (
                setClass('text-light'),
                $lang->block->productoverview->productReleasedThisYear
            )
        ),
        col
        (
            setClass('text-center w-1/3'),
            set::justify('center'),
            span
            (
                setClass('text-2xl font-bold text-important leading-relaxed'),
                $releaseCount
            ),
            span
            (
                setClass('text-light'),
                $lang->block->productoverview->releaseCount
            )
        )
    )
);

render();
