<?php
declare(strict_types=1);
/**
 * The change password view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

$reason = $app->user->modifyPasswordReason;

modalHeader
(
    set::title($lang->my->changePassword)
);

formPanel
(
    on::change('#originalPassword,#password1,#password2', 'changePassword'),
    on::click('button[type=submit]', 'clickSubmit'),
    formGroup
    (
        setStyle(array('align-items' => 'center')),
        set::label($lang->user->account),
        set::control('hidden'),
        set::name('account'),
        set::value($user->account),
        set::required(true),
        $user->account
    ),
    formGroup
    (
        set::label($lang->user->originalPassword),
        set::required(true),
        input
        (
            set::type('password'),
            set::name('originalPassword')
        )
    ),
    formGroup
    (
        set::label($lang->user->newPassword),
        set::required(true),
        password(set::checkStrength(true))
    ),
    formGroup
    (
        set::label($lang->user->password2),
        set::required(true),
        input
        (
            set::type('password'),
            set::name('password2')
        )
    ),
    input
    (
        set::type('hidden'),
        set::name('passwordLength'),
        set::value(0)
    ),
    !empty($reason) ? div
    (
        setClass('alert alert-info'),
        $lang->admin->safe->common . ' : ' . ($reason == 'weak' ? $lang->admin->safe->changeWeak : $lang->admin->safe->$reason)
    ) : null
);

input
(
    set::type('hidden'),
    set::name('verifyRand'),
    set::value($rand)
);

render();
