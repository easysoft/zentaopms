<?php
declare(strict_types=1);
/**
 * The activate view file of extension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     extension
 * @link        https://www.zentao.net
 */
namespace zin;

if(!empty($error))
{
    div
    (
        set::class('text-danger'),
        html($error)
    );
}
else
{
    div
    (
        set::class('p-2'),
        div
        (
            set::class('mb-3'),
            span
            (
                set::class('article-h1'),
                $title,
            )
        ),
        div
        (
            set::class('text-center'),
            btn
            (
                set::type('primary'),
                set::url(createLink('extension', 'browse', "status=installed")),
                $lang->extension->viewInstalled
            )
        )
    );
}

render();

