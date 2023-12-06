<?php
declare(strict_types=1);
/**
 * The uninstall view file of extension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     extension
 * @link        https://www.zentao.net
 */
namespace zin;

if(!empty($removeCommands)) $removeCommands = implode('<br />', $removeCommands);

if(isset($confirm) && $confirm == 'no')
{
    div
    (
        setClass('alert bg-white text-warning'),
        icon
        (
            'exclamation-sign',
            setClass('alert-icon')
        ),
        $lang->extension->confirmUninstall,
        a
        (
            set('href', '#'),
            set('load-url', createLink('extension', 'uninstall', "extension=$code&confirm=yes")),
            set::onclick('window.loadUrl(this)'),
            $lang->extension->uninstall
        )
    );
}
elseif(!empty($error))
{
    div
    (
        setClass('alert bg-white text-warning'),
        icon
        (
            'exclamation-sign',
            set::size('2x'),
            setClass('alert-icon')
        ),
        div
        (
            div
            (
                setClass('alert-heading'),
                $lang->extension->uninstallFailed
            ),
            p
            (
                setClass('alert-content py-4'),
                html($error)
            )
        )
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
                setClass('article-h2 leading-8 mb-2'),
                $title
            )
        ),
        (!empty($backupFile) || !empty($removeCommands)) ? div
        (
            setClass('alert'),
            icon
            (
                'check-circle',
                setClass('alert-icon')
            ),
            div
            (
                setClass('alert-content'),
                !empty($backupFile) ? p
                (
                    sprintf($lang->extension->backDBFile, $backupFile)
                ) : null,
                $removeCommands ? p
                (
                    setClass('font-bold'),
                    $lang->extension->unremovedFiles
                ) : null,
                $removeCommands ? html($removeCommands) : null
            )
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
                set('data-dismiss', 'modal')
            )
        )
    );
}

render();

