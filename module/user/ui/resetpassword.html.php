<?php
declare(strict_types=1);
/**
 * The resetPassword view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('expired', $expired);

$content = $expired ? div
(
    setClass('alert-danger p-4 mb-6'),
    $lang->user->linkExpired,
    html(sprintf($lang->user->jumping, inlink('login')))
) : formPanel
(
    setClass('reset-password w-full'),
    set::title($lang->user->resetPassword),
    set::actions(array()),
    formRow
    (
        formGroup
        (
            set::label($lang->user->password),
            set::required(true),
            password
            (
                set::checkStrength(true)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->user->password2),
            set::required(true),
            set::control('password'),
            set::name('password2'),
            set::value('')
        )
    ),
    formRow
    (
        setClass('hidden'),
        formGroup
        (
            set::name('passwordLength'),
            set::value(0)
        ),
        formGroup
        (
            set::name('verifyRand'),
            set::value($rand)
        )
    ),
    formRow
    (
        setClass('form-actions'),
        toolbar
        (
            btn(set(array('text' => $lang->user->submit, 'btnType' => 'submit', 'type' => 'primary', 'class' => 'mx-4'))),
            btn(set(array('text' => $lang->goback, 'url' => createLink('user', 'login'), 'back' => true, 'class' => 'mx-4')))
        )
    )
);

set::zui(true);
div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        $content
    )
);

render('pagebase');
