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

jsVar('projectID', $projectID);
jsVar('programID', $programID);

$fromItems = array();
foreach($lang->stakeholder->fromList as $fromKey => $fromName) $fromItems[] = array('text' => $fromName, 'value' => $fromKey);

$keyItems = array();
foreach($lang->stakeholder->keyList as $key => $keyName) $keyItems[] = array('text' => $keyName, 'value' => $key);

formPanel
(
    on::change('[name=from]', 'toggleUser'),
    on::change('[name=newUser]', 'toggleNewUserInfo'),
    on::change('[name=newCompany]', 'toggleCompany'),
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
        radioList
        (
            set::name('from'),
            set::value('team'),
            set::inline(true),
            set::items($fromItems)
        )
    ),
    formGroup
    (
        set::label($lang->stakeholder->isKey),
        set::width('1/2'),
        radioList
        (
            set::name('key'),
            set::value('0'),
            set::inline(true),
            set::items($keyItems)
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
            span
            (
                setClass('input-group-addon hidden'),
                checkbox
                (
                    set::name('newUser'),
                    set::text($lang->stakeholder->add)
                )
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
            picker
            (
                setClass('company-picker'),
                set::name('company'),
                set::items($companys)
            ),
            input
            (
                set::name('companyName'),
                setClass('hidden')
            ),
            span
            (
                setClass('input-group-addon'),
                checkbox
                (
                    set::name('newCompany'),
                    set::text($lang->stakeholder->add)
                )
            )
        )
    )),
    formGroup
    (
        set::label($lang->stakeholder->nature),
        set::control(array('name' => 'nature', 'control' => 'editor'))
    ),
    formGroup
    (
        set::label($lang->stakeholder->analysis),
        set::control(array('name' => 'analysis', 'control' => 'editor'))
    ),
    formGroup
    (
        set::label($lang->stakeholder->strategy),
        set::control(array('name' => 'strategy', 'control' => 'editor'))
    )
);

render();
