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
    div
    (
        set('class', 'flex block-base'),
        col
        (
            set('class', 'text-center w-1/3'),
            set::justify('center'),
            a
            (
                set('href', $this->createLink('product', 'all', 'browseType=all')),
                set('class', 'text-2xl font-bold leading-relaxed'),
                $totalProductCount
            ),
            span
            (
                set('class', 'text-light'),
                $lang->block->productoverview->totalProductCount
            )
        ),
        col
        (
            set('class', 'text-center w-1/3'),
            set::justify('center'),
            span
            (
                set('class', 'text-2xl font-bold leading-relaxed'),
                $productReleasedThisYear
            ),
            span
            (
                set('class', 'text-light'),
                $lang->block->productoverview->productReleasedThisYear
            )
        ),
        col
        (
            set('class', 'text-center w-1/3'),
            set::justify('center'),
            span
            (
                set('class', 'text-2xl font-bold text-important leading-relaxed'),
                $releaseCount
            ),
            span
            (
                set('class', 'text-light'),
                $lang->block->productoverview->releaseCount
            )
        )
    )
);

render();
