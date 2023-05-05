<?php
declare(strict_types=1);
/**
* The execution overview block view file of block module of ZenTaoPMS.
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
                    set('class', 'text-2xl font-bold leading-10 text-primary'),
                    132
                ),
                span
                (
                    set('class', 'text-light'),
                    '执行总量'
                )
            )
        ),
        cell
        (
            set('class', 'border-right'),
            set('style', ['width' => '25%']),
            col
            (
                set('class', 'text-center'),
                span
                (
                    set('class', 'text-2xl font-bold leading-10'),
                    12
                ),
                span
                (
                    set('class', 'text-light'),
                    '今年完成'
                )
            )
        ),
        cell
        (

        )
    )
);

render();
