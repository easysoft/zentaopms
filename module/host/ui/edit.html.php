<?php
declare(strict_types=1);
/**
 * The create view file of host module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     host
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('host', $host);

formPanel
(
    set::id('hostCreateForm'),
    set::title($lang->host->edit),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::name('CD'),
            set::label($lang->host->CD),
            set::control('picker'),
            set::items($lang->host->CDlist),
            set::required(true),
            set::value($host->CD)
        ),
        formGroup
        (
            set::width('1/3'),
            set::name('name'),
            set::label($lang->host->name),
            set::value($host->name)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->host->group),
            set::control('picker'),
            set::name('group'),
            set::items($optionMenu),
            set::value($host->group ? $host->group : 0)
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->host->serverRoom),
            set::control('picker'),
            set::name('serverRoom'),
            set::items($rooms),
            set::value($host->serverRoom)
        )
    ),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->host->admin),
        set::control('picker'),
        set::name('admin'),
        set::items($accounts),
        set::value($host->admin)
    ),
    formRow
    (
        setID('spugConfig'),
        setClass('hidden'),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->host->password),
            set::name('password'),
            set::control('password'),
            set::value($lang->host->defaultPWD)
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->host->sshPort),
            set::name('sshPort'),
            set::value(zget($host, 'sshPort', ''))
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->host->osName),
            set::control('picker'),
            set::name('osName'),
            set::items($lang->host->osNameList),
            set::value($osName ? $osName : $host->osName),
            on::change('osChange')
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->host->osVersion),
            set::control('picker'),
            set::name('osVersion'),
            set::items($lang->host->{"{$osName}List"}),
            set::value($host->osVersion)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::name('intranet'),
            set::label($lang->host->intranet),
            set::value($host->intranet)
        ),
        formGroup
        (
            set::width('1/3'),
            set::name('extranet'),
            set::label($lang->host->extranet),
            set::value($host->extranet)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::name('cpuNumber'),
            set::label($lang->host->cpuNumber),
            set::value($host->cpuNumber)
        ),
        formGroup
        (
            set::width('1/3'),
            set::name('memory'),
            set::label($lang->host->memory),
            set::value($host->memory),
            set::control(array
            (
                'type'        => 'inputControl',
                'suffix'      => 'GB',
                'suffixWidth' => 40
            ))
        )
    ),
    formGroup
    (
        set::width('1/3'),
        set::name('diskSize'),
        set::label($lang->host->diskSize),
        set::value($host->diskSize),
        set::control(array
        (
            'type'        => 'inputControl',
            'suffix'      => 'GB',
            'suffixWidth' => 40
        ))
    ),
    formGroup
    (
        set::width('2/3'),
        set::name('desc'),
        set::label($lang->host->desc),
        set::control(array('type' => 'textarea', 'rows' => 3)),
        set::value($host->desc)
    ),
    formGroup
    (
        set::width('1/3'),
        set::name('status'),
        set::control('radioList'),
        set::label($lang->host->status),
        set::value($host->status ? $host->status : 'online'),
        set::inline(true),
        set::items($lang->host->statusList)
    )
);
