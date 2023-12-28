<?php
declare(strict_types=1);
/**
 * The editProfile view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

$contacts = array();
if(!empty($config->user->contactField))
{
    foreach(explode(',', $config->user->contactField) as $i => $field)
    {
        if($i % 2 == 0) $contactGroup = array();

        $contactGroup[] = formGroup
        (
            set::width('1/2'),
            set::label($lang->user->{$field}),
            set::name($field),
            set::value($user->{$field})
        );

        if($i % 2 == 1) $contacts[] = formRow($contactGroup);
    }
}

formPanel
(
    to::heading
    (
        div
        (
            setClass('flex items-center gap-2'),
            entityLabel
            (
                set::level(1),
                set::text($lang->my->editProfile)
            )
        )
    ),
    on::change('#password1, #password, #verifyPassword', 'changePassword'),
    on::click('button[type="submit"]', 'encryptPassword'),
    formRowGroup(set::title($lang->my->form->lblBasic)),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->realname),
            set::name('realname'),
            set::value($user->realname)
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->email),
            set::name('email'),
            set::value($user->email)
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
                set::inline(true),
                set::name('gender'),
                set::items($lang->user->genderList),
                set::value($user->gender)
            )
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->join),
            set::control('datePicker'),
            set::name('join'),
            set::value($user->join)
        )
    ),
    formRowGroup(set::title($lang->my->form->lblAccount)),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->account),
            set::name('account'),
            set::value($user->account),
            set::disabled(true)
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->commiter),
            set::name('commiter'),
            set::value($user->commiter)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->password),
            password(set::checkStrength(true))
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->password2),
            set::control('password'),
            set::name('password2')
        )
    ),
    formRowGroup(set::title($lang->my->form->lblBasic)),
    $contacts,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->address),
            set::name('address'),
            set::value($user->address)
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->zipcode),
            set::name('zipcode'),
            set::value($user->zipcode)
        )
    ),
    formRowGroup(set::title($lang->user->verify)),
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
    formHidden('visions[]', $user->visions),
    formHidden('passwordLength', 0),
    formHidden('passwordStrength', 0),
);

formHidden('verifyRand', $rand);

render();

