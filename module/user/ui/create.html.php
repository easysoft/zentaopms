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

$visionList       = $this->user->getVisionList();
$showVisionList   = count($visionList) > 1;
$passwordStrength = zget($lang->user->placeholder->passwordStrength, !empty($config->safe->mode) ? $config->safe->mode : 0, '');

$visionsCheckbox = array();
foreach($visionList as $key => $label)
{
    $visionsCheckbox[] = checkbox
    (
        set::id("visions$key"),
        set::name('visions[]'),
        set::text($label),
        set::value($key),
        set::checked($key == 'rnd')
    );
}

formPanel
(
    set::id('createUser'),
    on::change('input[name=type]', 'changeType'),
    on::change('input[name=role]', 'changeRole'),
    on::change('#addCompany', 'changeAddCompany'),
    on::change('input[name^=visions]', 'changeVision'),
    on::change('#password1,#password2,#verifyPassword', 'changePassword'),
    on::click('button[type=submit]', 'clickSubmit'),
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
                set::value('inside')
            )
        )
    ),
    formRow
    (
        set::id('companyBox'),
        set::className('hidden'),
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
                    set::value('')
                ),
                span
                (
                    set('class', 'input-group-addon'),
                    checkbox
                    (
                        set::id('addCompany'),
                        set::name('new[]'),
                        set::value('0'),
                        set::text($lang->company->create)
                    )
                )
            )
        )
    ),
    formRow
    (
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
            set::value('')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->password),
            set::required(true),
            password(set::checkStrength(true)),
            set::tip($passwordStrength),
            set::tipClass('form-tip')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->password2),
            set::control('password'),
            set::name('password2'),
            set::value('')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::className('flex gap-4 items-center ' . ($showVisionList ? '' : 'hide')),
            set::label($lang->user->visions),
            set::required(true),
            $visionsCheckbox
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->realname),
            set::name('realname'),
            set::value('')
        )
    ),
    formRow
    (
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
            set::value(''),
            set::tip($lang->user->placeholder->role),
            set::tipClass('form-tip')
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
            set::tip($lang->user->placeholder->group),
            set::tipClass('form-tip')
        )
    ) : null,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->email),
            set::name('email'),
            set::value('')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->commiter),
            set::name('commiter'),
            set::value()
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
            set::value(''),
            set::placeholder($lang->user->placeholder->verify)
        )
    ),
    input
    (
        set::className('hidden'),
        set::name('verifyRand'),
        set::value($rand)
    ),
    input
    (
        set::className('hidden'),
        set::id('passwordLength'),
        set::name('passwordLength'),
        set::value($rand)
    )
);

render();
