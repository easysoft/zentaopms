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
        setClass('text-danger'),
        html($error)
    );
}
else
{
    set::closeBtn(false);

    div
    (
        setClass('p-2'),
        div
        (
            setClass('flex mb-3'),
            icon
            (
                'exclamation-sign',
                set::size('2x'),
                setClass('text-warning mr-4')
            ),
            div
            (
                setClass('text-md font-bold leading-8 mb-2'),
                $title
            )
        ),
        div
        (
            setClass('text-center'),
            btn
            (
                setClass('mr-4'),
                set::type('primary'),
                set('load-url', createLink('extension', 'browse', "status=installed")),
                set::onclick('window.loadParentUrl(this)'),
                $lang->extension->viewInstalled
            ),
            btn
            (
                $lang->cancel,
                set('data-dismiss', 'modal'),
                set::onclick('window.loadPage()')
            )
        )
    );
}

render();
