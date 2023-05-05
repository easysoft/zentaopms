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
        set('class', 'flex'),
        cell
        (
            set('class', 'border-right'),
            set('style', ['width' => '25%']),
            col
            (
                set('class', 'text-center'),
                span
                (
                    set('class', 'tile-amount text-primary'),
                    132
                ),
                span
                (
                    set('class', 'text-light'),
                    '产品总量'
                )
            )
        ),
        cell
        (
            set('class', 'border-right flex justify-around'),
            set('style', ['width' => '50%']),
            col
            (
                set('class', 'text-center'),
                span
                (
                    set('class', 'tile-amount text-primary'),
                    12
                ),
                span
                (
                    set('class', 'text-light'),
                    '产品线'
                )
            ),
            col
            (
                set('class', 'text-center'),
                span
                (
                    set('class', 'tile-amount text-primary'),
                    100
                ),
                span
                (
                    set('class', 'text-light'),
                    '今年发布'
                )
            )
        ),
        cell
        (
            set('style', ['width' => '25%']),
            col
            (
                set('class', 'text-center'),
                span
                (
                    set('class', 'tile-amount text-important'),
                    16
                ),
                span
                (
                    set('class', 'text-light'),
                    '发布里程碑'
                )
            )
        )
    )
);

render();
