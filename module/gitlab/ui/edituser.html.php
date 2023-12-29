<?php
declare(strict_types=1);
/**
 * The edituser view file of gitlab module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->gitlab->user->edit),
    set::back('gitlab-browseuser'),
    h::input
    (
        set::name('id'),
        set::type('hidden'),
        set::value($user->id)
    ),
    formGroup
    (
        set::name('account'),
        set::label($lang->gitlab->user->bind),
        set::required(true),
        set::items($userPairs),
        set::value($zentaoBindAccount),
        set::width('1/2')
    ),
    formGroup
    (
        set::name('name'),
        set::label($lang->gitlab->user->name),
        set::required(true),
        set::width('1/2'),
        set::value($user->name)
    ),
    formGroup
    (
        set::name('username'),
        set::label($lang->gitlab->user->username),
        set::required(true),
        set::width('1/2'),
        set::value($user->username)
    ),
    formGroup
    (
        set::name('email'),
        set::label($lang->gitlab->user->email),
        set::required(true),
        set::width('1/2'),
        set::value($user->email)
    ),
    formGroup
    (
        set::name('password'),
        set::label($lang->gitlab->user->password),
        set::required(true),
        set::width('1/2'),
        set::type('password')
    ),
    formGroup
    (
        set::name('password_repeat'),
        set::label($lang->gitlab->user->passwordRepeat),
        set::required(true),
        set::width('1/2'),
        set::type('password')
    ),
    formRow
    (
        setClass('hidden'),
        formGroup
        (
            set::name('projects_limit'),
            set::label($lang->gitlab->user->projectsLimit),
            set::placeholder($lang->gitlab->user->projectsLimit),
            set::value($user->projects_limit),
            set::width('1/2')
        )
    ),
    formGroup
    (
        set::name('can_create_group'),
        set::label($lang->gitlab->user->canCreateGroup),
        set::control(array('type' => 'checkbox', 'checked' => $user->can_create_group ? true : false)),
        set::value('1')
    ),
    formGroup
    (
        set::name('external'),
        set::label($lang->gitlab->user->external),
        set::control(array('type' => 'checkbox', 'text' => $lang->gitlab->user->externalTip, 'checked' => $user->external ? true : false)),
        set::value('1')
    ),
    formGroup
    (
        set::name('avatar'),
        set::label($lang->gitlab->user->avatar),
        set::control(array('type' => 'file', 'class' => 'hidden', 'id' => 'files')),
        h::div
        (
            set::id('avatarUpload'),
            setClass('text-center'),
            html(html::avatar(array('avatar'=> $user->avatar_url ? $user->avatar_url : 'theme/default/images/repo/avatar.jpeg', 'account'=>''), 50)),
            h::a
            (
                set::href('javascript:void(0)'),
                setClass('btn-avatar'),
                set::id('avatarUploadBtn'),
                set::title($lang->gitlab->user->avatar),
                icon
                (
                    setClass('icon icon-pencil icon-2x'),
                    set::name('')
                )
            )
        )
    )
);

render();
