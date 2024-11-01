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
    set::id('hostEditForm'),
    set::title($lang->host->edit),
    on::change('[name="CD"]')->call('showSpugConfig', jsRaw('this')),
    formRow
    (
        setID('groupRow'),
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
    formRow
    (
        formGroup
        (
            setID('openName'),
            set::width('1/3'),
            set::name('name'),
            set::label($lang->host->name),
            set::value($host->name)
        ),
        formGroup
        (
            setID('sshPort'),
            setClass('hidden'),
            set::width('1/3'),
            set::label($lang->host->sshPort),
            set::required(true),
            set::name('sshPort'),
            set::value(zget($host, 'sshPort') ? $host->sshPort : '')
        ),
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
            setClass('useManual hidden'),
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
        setID('spugConfig'),
        setClass('hidden'),
        formGroup
        (
            setID('admin'),
            set::width('1/3'),
            set::label($lang->host->account),
            set::name('admin'),
            set::value($host->admin)
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->host->password),
            set::name('password'),
            set::control('password'),
            set::required(true),
            set::value($lang->host->defaultPWD)
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
            setClass('useSpug'),
            set::width('1/3'),
            set::label($lang->host->osVersion),
            set::control('picker'),
            set::name('osVersion'),
            set::items($lang->host->{"{$osName}List"}),
            set::value($host->osVersion)
        ),
        formGroup
        (
            setClass('useManual hidden'),
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
            setClass('useSpug'),
            set::width('1/3'),
            set::name('extranet'),
            set::label($lang->host->extranet),
            set::value($host->extranet)
        ),
        formGroup
        (
            set::width('1/3'),
            set::name('cpuNumber'),
            set::label($lang->host->cpuNumber),
            set::control(array('type' => 'number', 'min' => 1)),
            set::value(zget($host, 'cpuNumber', '') ? $host->cpuNumber : '')
        ),
        formGroup
        (
            setClass('useManual hidden'),
            set::width('1/3'),
            set::name('memory'),
            set::label($lang->host->memory),
            set::value(zget($host, 'memory', '') ? $host->memory : ''),
            set::control(array
            (
                'type'        => 'inputControl',
                'suffix'      => 'GB',
                'suffixWidth' => 40
            ))
        )
    ),
    formRow
    (
        formGroup
        (
            setClass('useSpug'),
            set::width('1/3'),
            set::name('memory'),
            set::label($lang->host->memory),
            set::value(zget($host, 'memory', '') ? $host->memory : ''),
            set::control(array
            (
                'type'        => 'inputControl',
                'suffix'      => 'GB',
                'suffixWidth' => 40
            ))
        ),
        formGroup
        (
            set::width('1/3'),
            set::name('diskSize'),
            set::label($lang->host->diskSize),
            set::value(zget($host, 'diskSize', '') ? $host->diskSize : ''),
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
