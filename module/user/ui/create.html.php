<?php
declare(strict_types=1);
/**
 * The create view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('roleGroup', $roleGroup);

formPanel
(
    set::id('createUser'),
    on::change('input[name=type]', 'changeType'),
    on::change('input[name=role]', 'changeRole'),
    on::change('input[name^=visions]', 'changeVision'),
    on::change('#password1,#password2,#verifyPassword', 'changePassword'),
    on::click('button[type=submit]', 'encryptPassword'),
    set::title($title),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->type),
            radioList
            (
                set::inline(true),
                set::name('type'),
                set::items($lang->user->typeList),
                set::value($type)
            )
        )
    ),
    formRow
    (
        set::id('companyBox'),
        $type == 'inside' ? set::className('hidden') : null,
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->company),
            inputGroup
            (
                picker
                (
                    set::control('picker'),
                    set::name('company'),
                    set::items($companies),
                ),
                input
                (
                    set::name('newCompany'),
                    setClass('hidden')
                ),
                checkbox
                (
                    on::change('toggleNew'),
                    set::id('new'),
                    set::name('new'),
                    set::value(1),
                    set::text($lang->company->create),
                    set::rootClass('btn'),
                    width('96px')
                )
            )
        )
    ),
    formRow
    (
        $type == 'inside' ? null : setClass('hidden'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->dept),
            set::control('picker'),
            set::name('dept'),
            set::items($depts),
            set::value($deptID)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->account),
            set::name('account'),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->password),
            set::required(true),
            password(set::checkStrength(true))
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->abbr->password2),
            set::required(true),
            set::control('password'),
            set::name('password2'),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::className('flex items-center ' . (count($visions) > 1 ? '' : 'hide')),
            set::label($lang->user->visions),
            set::required(true),
            checkList
            (
                set::inline(true),
                set::name('visions[]'),
                set::items($visions),
                set::value($config->vision)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->realname),
            set::name('realname'),
        )
    ),
    formRow
    (
        $type == 'inside' ? null : setClass('hidden'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->join),
            set::control('date'),
            set::name('join'),
            set::value(date('Y-m-d'))
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->role),
            set::control('picker'),
            set::name('role'),
            set::items($lang->user->roleList),
            set::placeholder($lang->user->placeholder->role)
        )
    ),
    common::hasPriv('group', 'managemember') ? formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->group),
            set::control(array("type" => "picker","multiple" => true)),
            set::name('group[]'),
            set::items($groupList),
            set::placeholder($lang->user->placeholder->group)
        )
    ) : null,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->email),
            set::name('email'),
        )
    ),
    formRow
    (
        $type == 'inside' ? null : setClass('hidden'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->commiter),
            set::name('commiter'),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->gender),
            radioList
            (
                set::name('gender'),
                set::items($lang->user->genderList),
                set::value('m'),
                set::inline(true)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->verifyPassword),
            set::control('password'),
            set::required(true),
            set::name('verifyPassword'),
            set::placeholder($lang->user->placeholder->verify)
        )
    ),
    formRow
    (
        setClass('hidden'),
        input(set::name('passwordLength'), set::value(0)),
        input(set::name('passwordStrength'), set::value(0))
    )
);

formHidden('verifyRand', $rand);

render();
