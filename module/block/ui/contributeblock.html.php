<?php
declare(strict_types=1);
/**
* The contribute block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      LiuRuoGu <liuruogu@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$blocks = array();

foreach($data as $code => $value)
{
    $blocks[] = cell
    (
        set('class', 'w-1/3'),
        col
        (
            set('justify', 'around'),
            set('class', 'text-center'),
            span(zget($lang->block, $code)),
            a
            (
                set('class', 'font-bold text-2xl text-darker leading-loose'),
                $value
            )
        )
    );
}

panel
(
    div
    (
        set('class', 'flex flex-wrap gap-y-2'),
        $blocks
    )
);

render('|fragment');
