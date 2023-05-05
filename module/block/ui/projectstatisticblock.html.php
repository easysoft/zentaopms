<?php
declare(strict_types=1);
/**
* The project block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wangyuting <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

panel
(
    set('class', 'welcome-block'),
    ul
    (
        set('class', 'nav nav-tabs'),
        li
        (
            set('class', 'nav-item'),
            a
            (
                set('href', '#tabCount1'),
                '标签1'
            )
        ),
        li
        (
            set('class', 'nav-item'),
            a
            (
                set('href', '#tabCount2'),
                '标签2'
            )
        )
    ),
    div
    (
        set('class', 'tab-content'),
        div
        (
            set('class', 'tab-pane'),
            set('id', 'tabCount1'),
            "我是标签1"
        )
        div
        (
            set('class', 'tab-pane'),
            set('id', 'tabCount2'),
            "我是标签2"
        )
    )
)

render();
