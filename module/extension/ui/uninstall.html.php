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
        set::class('alert bg-white text-warning'),
        icon
        (
            'exclamation-sign',
            set::class('alert-icon')
        ),
        $lang->extension->confirmUninstall,
        a
        (
            set('href', createLink('extension', 'uninstall', "extension=$code&confirm=yes")),
            $lang->extension->uninstall
        )
    );
}
elseif(!empty($error))
{
    div
    (
        set::class('alert bg-white text-warning'),
        icon
        (
            'exclamation-sign',
            set::size('2x'),
            set::class('alert-icon')
        ),
        div
        (
            div 
            (
                set::class('alert-heading'),
                $lang->extension->uninstallFailed
            ),
            p
            (
                set::class('alert-content py-4'),
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
        set::class('p-2'),
        div
        (
            set::class('flex mb-3'),
            icon
            (
                'exclamation-sign',
                set::size('2x'),
                set::class('text-warning mr-4'),
            ),
            div
            (
                set::class('article-h2 leading-8 mb-2'),
                $title,
            )
        ),
        (!empty($backupFile) || !empty($removeCommands)) ? div
        (
            set::class('alert'),
            icon
            (
                'check-circle',
                set::class('alert-icon')
            ),
            div
            (
                set::class('alert-content'),
                !empty($backupFile) ? p
                (
                    sprintf($lang->extension->backDBFile, $backupFile)
                ) : null,
                $removeCommands ? p
                (
                    set::class('font-bold'),
                    $lang->extension->unremovedFiles,
                ) : null,
                $removeCommands ? html($removeCommands) : null,
            )
        ) : null,
        div
        (
            set::class('text-center'),
            btn
            (
                set::class('mr-4'),
                set::type('primary'),
                set::url(createLink('extension', 'browse', "status=available")),
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

