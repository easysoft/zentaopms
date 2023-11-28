<?php
declare(strict_types=1);
/**
 * The reset view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;

$content = $needCreateFile ? div
(
    setClass('create-file cell flex justify-center mt-24'),
    panel
    (
        setClass('create-file-panel w-full'),
        set::title($lang->user->resetPassword),
        cell
        (
            setClass('alert-info p-4 mb-6'),
            html(sprintf($lang->user->noticeResetFile, $resetFileName))
        ),
        cell
        (
            setClass('flex justify-center mb-4'),
            btn
            (
                setClass('px-8 mx-4'),
                set::type('primary'),
                set::url(inlink('reset')),
                $lang->refresh
            ),
            backBtn
            (
                setClass('px-8 mx-4'),
                set::back('GLOBAL'),
                $lang->goback
            )
        )
    )
) : div
(
    setClass('reset-password cell flex justify-center mt-24'),
    formPanel
    (
        setClass('reset-form w-full'),
        set::title($lang->user->resetPassword),
        on::change('#password1,#password2', 'changePassword'),
        on::click('button[type=submit]', 'encryptPassword'),
        formRow
        (
            formGroup
            (
                set::label($lang->user->account),
                set::name('account'),
                set::required(true)
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->user->password),
                password(set::checkStrength(true)),
                set::required(true)
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->user->abbr->password2),
                set::control('password'),
                set::name('password2'),
                set::required(true)
            )
        ),
        formRow
        (
            setClass('hidden'),
            input(set::name('passwordLength'), set::value(0)),
            input(set::name('passwordStrength'), set::value(0))
        )
    ),
    formHidden('verifyRand', $rand)
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
