<?php
declare(strict_types=1);
/**
 * The edit view file of zanode module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     zanode
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('linuxList', $config->zanode->linuxList);
jsVar('windowsList', $config->zanode->windowsList);

formPanel
(
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $lang->zanode->editAction,
        icon
        (
            'help',
            set('data-toggle', 'tooltip'),
            set('data-placement', 'right'),
            set('data-title', $lang->zanode->tips),
            set('data-type', 'white'),
            set('data-class-name', 'text-gray border border-light'),
            setClass('text-gray')
        )
    )),
    $zanode->hostType != 'physics' ? formGroup
    (
        set::name(''),
        set::label($lang->zanode->hostName),
        set::value($host->name),
        set::width('1/2'),
        set::readonly(true)
    ) : null,
    formGroup
    (
        set::name('name'),
        set::label($lang->zanode->name),
        set::required(true),
        set::width('1/2'),
        set::value($zanode->name),
        set::readonly($zanode->hostType != 'physics' ? true : false)
    ),
    $zanode->hostType != 'physics' ? formGroup
    (
        set::name(''),
        set::label($lang->zanode->image),
        set::width('1/2'),
        set::value($image->name),
        set::readonly(true)
    ) : formGroup
    (
        set::name('extranet'),
        set::label($lang->zahost->IP),
        set::readonly(true),
        set::width('1/2'),
        set::value($zanode->extranet)
    ),
    formGroup
    (
        set::label($lang->zanode->cpuCores),
        set::width('1/2'),
        set::name(''),
        set::value(zget($config->zanode->os->cpuCores, $zanode->cpuCores)),
        set::readonly(true)
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->zanode->memory),
            set::width('1/2'),
            inputGroup
            (
                input
                (
                    set::name('memory'),
                    set::readonly(true),
                    set::value($zanode->memory)
                ),
                'GB'
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->zanode->diskSize),
            set::width('1/2'),
            inputGroup
            (
                input
                (
                    set::name('diskSize'),
                    set::readonly(true),
                    set::value($zanode->diskSize)
                ),
                'GB'
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('osName'),
            set::label($lang->zanode->osName),
            set::readonly(true),
            set::width('1/2'),
            set::value($zanode->hostType != 'physics' ? $zanode->osName : zget($config->zanode->linuxList, $zanode->osName, zget($config->zanode->windowsList, $zanode->osName)))
        )
    ),
    formGroup
    (
        set::label($lang->zanode->desc),
        editor
        (
            set::name('desc'),
            html($zanode->desc)
        )
    )
);

render();
