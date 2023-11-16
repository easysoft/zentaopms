<?php
declare(strict_types=1);
/**
 * The create view file of stakeholder module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      stakeholder <stakeholder@easycorp.ltd>
 * @package     stakeholder
 * @link        https://www.zentao.net
 */

namespace zin;

dropmenu();

formPanel
(
    set::url(createLink('stakeholder', 'create', "objectID=$objectID")),
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $lang->stakeholder->create
    )),
    formGroup
    (
        set::label($lang->stakeholder->from),
        set::width('1/2'),
        inputGroup(array_map
        (
            function($val, $text)
            {
                return radio
                (
                    set::name('from'),
                    set::value($val),
                    set::text($text),
                    set::checked($val == 'team'),
                    on::change('onChangeStakeholderType')
                );
            },
            array_keys($lang->stakeholder->fromList),
            array_values($lang->stakeholder->fromList)
        ))
    ),
    formGroup
    (
        set::label($lang->stakeholder->isKey),
        set::width('1/2'),
        inputGroup
        (
            array_map
            (
                function($val, $text)
                {
                    return radio
                    (
                        set::name('key'),
                        set::value($val),
                        set::text($text),
                        set::checked($val == 0)
                    );
                },
                array_keys($lang->stakeholder->keyList),
                array_values($lang->stakeholder->keyList)
            )
        )
    ),
    formRow(formGroup
    (
        set::label($lang->stakeholder->user),
        set::width('1/2'),
        set::required(true),
        inputGroup
        (
            picker
            (
                set::name('user'),
                set::items($members)
            ),
            checkbox
            (
                set::rootClass('newuser-checkbox hidden w-20 justify-end items-center'),
                set::name('newUser'),
                on::click('onChangeNewUserCheckbox'),
                set::text($lang->stakeholder->add)
            )
        )
    )),
    formRow(setClass('user-info hidden'), formGroup
    (
        set::label($lang->stakeholder->name),
        set::width('1/2'),
        set::required(true),
        set::control(array
        (
            'type' => 'inputControl',
            'name' => 'name'
        ))
    )),
    formRow(setClass('user-info hidden'), formGroup
    (
        set::label($lang->stakeholder->phone),
        set::width('1/2'),
        set::control(array
        (
            'type' => 'inputControl',
            'name' => 'phone'
        ))
    )),
    formRow(setClass('user-info hidden'), formGroup
    (
        set::label($lang->stakeholder->qq),
        set::width('1/2'),
        set::control(array
        (
            'type' => 'inputControl',
            'name' => 'qq'
        ))
    )),
    formRow(setClass('user-info hidden'), formGroup
    (
        set::label($lang->stakeholder->weixin),
        set::width('1/2'),
        set::control(array
        (
            'type' => 'inputControl',
            'name' => 'weixin'
        ))
    )),
    formRow(setClass('user-info hidden'), formGroup
    (
        set::label($lang->stakeholder->email),
        set::width('1/2'),
        set::control(array
        (
            'type' => 'inputControl',
            'name' => 'email'
        ))
    )),
    formRow(setClass('user-info hidden'), formGroup
    (
        set::label($lang->stakeholder->company),
        set::width('1/2'),
        set::required(true),
        inputGroup
        (
            select
            (
                set::name('companySelect'),
                set::items($companys),
                on::change('onChooseCompany')
            ),
            input
            (
                set::name('company'),
                setClass('hidden')
            ),
            checkbox
            (
                set::name('new'),
                set::rootClass('w-20 justify-end items-center'),
                set::value(0),
                on::change('onChangeNewCompany'),
                set::text($lang->stakeholder->add)
            )
        )
    )),
    formGroup
    (
        set::label($lang->stakeholder->nature),
        set::control(array('name' => 'nature', 'type' => 'editor'))
    ),
    formGroup
    (
        set::label($lang->stakeholder->analysis),
        set::control(array('name' => 'analysis', 'type' => 'editor'))
    ),
    formGroup
    (
        set::label($lang->stakeholder->strategy),
        set::control(array('name' => 'strategy', 'type' => 'editor'))
    )
);

render();
