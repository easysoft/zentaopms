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

formPanel
(
    set::id('hostCreateForm'),
    set::title($lang->host->create),
    formRow
    (
        setID('groupRow'),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->host->group),
            set::control('picker'),
            set::name('group'),
            set::items($optionMenu)
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->host->serverRoom),
            set::control('picker'),
            set::name('serverRoom'),
            set::items($rooms)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::name('name'),
            set::label($lang->host->name)
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->host->osName),
            set::control('picker'),
            set::name('osName'),
            set::items($lang->host->osNameList),
            on::change('osChange')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::name('intranet'),
            set::label($lang->host->intranet)
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->host->osVersion),
            set::control('picker'),
            set::name('osVersion'),
            set::items(array())
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::name('extranet'),
            set::label($lang->host->extranet)
        ),
        formGroup
        (
            set::width('1/3'),
            set::name('cpuNumber'),
            set::label($lang->host->cpuNumber),
            set::control(array('type' => 'number', 'min' => 1))
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::name('memory'),
            set::label($lang->host->memory),
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
            set::control(array
            (
                'type'        => 'inputControl',
                'suffix'      => 'GB',
                'suffixWidth' => 40
            ))
        ),
    ),
    formGroup
    (
        set::width('2/3'),
        set::name('desc'),
        set::label($lang->host->desc),
        set::control(array('type' => 'textarea', 'rows' => 3))
    ),
    formGroup
    (
        set::name('status'),
        set::control('radioList'),
        set::label($lang->host->status),
        set::value('online'),
        set::inline(true),
        set::items($lang->host->statusList)
    )
);
