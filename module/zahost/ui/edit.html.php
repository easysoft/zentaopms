<?php
declare(strict_types=1);
/**
 * The edit view file of zahost module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     zahost
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $lang->zahost->editAction,
        icon
        (
            'help',
            set('data-toggle', 'tooltip'),
            set('data-placement', 'right'),
            set('data-title', $lang->zahost->tips),
            set('data-type', 'white'),
            set('data-class-name', 'text-gray border border-light'),
            setClass('text-gray')
        )
    )),
    formGroup
    (
        set::name('vsoft'),
        set::label($lang->zahost->vsoft),
        set::required(true),
        set::width('1/2'),
        set::items($lang->zahost->softwareList),
        set::value($host->vsoft)
    ),
    formGroup
    (
        set::name('hostType'),
        set::label($lang->zahost->zaHostType),
        set::required(true),
        set::width('1/2'),
        set::items($lang->zahost->zaHostTypeList),
        set::value($host->hostType)
    ),
    formGroup
    (
        set::name('name'),
        set::label($lang->zahost->name),
        set::required(true),
        set::width('1/2'),
        set::value($host->name)
    ),
    formGroup
    (
        set::name('extranet'),
        set::label($lang->zahost->extranet),
        set::disabled(true),
        set::width('1/2'),
        set::value($host->extranet)
    ),
    formGroup
    (
        set::name('cpuCores'),
        set::label($lang->zahost->cpuCores),
        set::required(true),
        set::width('1/2'),
        set::items($config->zahost->cpuCoreList),
        set::value($host->cpuCores)
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->zahost->memory),
            set::required(true),
            set::width('1/2'),
            inputGroup
            (
                input
                (
                    set::name('memory'),
                    set::value($host->memory)
                ),
                'GB'
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->zahost->diskSize),
            set::required(true),
            set::width('1/2'),
            inputGroup
            (
                input
                (
                    set::name('diskSize'),
                    set::value($host->diskSize)
                ),
                'GB'
            )
        )
    ),
    formGroup
    (
        set::label($lang->zahost->desc),
        editor
        (
            set::name('desc'),
            html($host->desc)
        )
    )
);

render();
