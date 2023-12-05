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
    setClass('text-center text-danger bg-danger-pale p-4 mx-auto'),
    setStyle(array('width' => '750px', 'margin-top' => '100px')),
    $lang->user->linkExpired,
    html(sprintf($lang->user->jumping, inlink('login')))
) : formPanel
(
    setStyle(array('width' => '500px', 'margin-top' => '100px')),
    set::title($lang->user->resetPWD),
    set::actions(array()),
    on::change('#password1,#password2', 'changePassword'),
    on::click('button[type=submit]', 'encryptPassword'),
    formRow
    (
        formGroup
        (
            set::label($lang->user->password),
            set::required(true),
            password(set::checkStrength(true))
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->user->password2),
            set::required(true),
            password(set::name('password2'))
        )
    ),
    formRow
    (
        setClass('hidden'),
        input(set::name('passwordLength'), set::value(0)),
        input(set::name('passwordStrength'), set::value(0))
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
    ),
    formHidden('verifyRand', $rand)
);

render('pagebase');
