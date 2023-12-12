<?php
declare(strict_types=1);
/**
 * The erase view file of extension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     extension
 * @link        https://www.zentao.net
 */
namespace zin;

set::closeBtn(false);

div
(
    setClass('p-2'),
    div
    (
        setClass('flex mb-3'),
        icon
        (
            'check-circle',
            set::size('2x'),
            setClass('text-success mr-4')
        ),
        div
        (
            setClass('article-h2 leading-8 mb-2 text-success'),
            $title
        )
    ),
    $removeCommands ? div
    (
        setClass('mb-3'),
        div
        (
            setClass('font-bold'),
            $lang->extension->unremovedFiles
        ),
        html(\implode('<br />', $removeCommands))
    ) : null,
    div
    (
        setClass('text-center'),
        btn
        (
            setClass('mr-4'),
            set::type('primary'),
            set('load-url', createLink('extension', 'browse', "status=available")),
            set::onclick('window.loadParentUrl(this)'),
            $lang->extension->viewAvailable
        ),
        btn
        (
            $lang->cancel,
            set('data-dismiss', 'modal'),
            set::onclick('window.loadPage()')
        )
    )
);

render();

