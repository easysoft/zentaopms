<?php
declare(strict_types=1);
/**
* The todo list block view file of block module of ZenTaoPMS.
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
        btn
        (
            set('class', 'btn-info'),
            '添加待办'
        )
    )
);

render();
