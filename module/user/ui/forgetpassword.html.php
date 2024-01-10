<?php
declare(strict_types=1);
/**
 * The forgetPassword view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);
div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        formPanel
        (
            setStyle(array('width' => '500px', 'margin-top' => '100px')),
            set::title($lang->user->resetPwdByMail),
            set::actions(array()),
            to::headingActions
            (
                a
                (
                    set::href(inlink('reset')),
                    $lang->user->resetPwdByAdmin
                )
            ),
            formRow
            (
                formGroup
                (
                    set::label($lang->user->account),
                    set::name('account'),
                    set::required(true),
                    set::placeholder($lang->user->placeholder->loginAccount)
                )
            ),
            formRow
            (
                formGroup
                (
                    set::label($lang->user->email),
                    set::name('email'),
                    set::required(true),
                    set::placeholder($lang->user->placeholder->email)
                )
            ),
            formRow
            (
                setClass('form-actions'),
                toolbar
                (
                    btn(set(array('text' => $lang->user->submit, 'btnType' => 'submit', 'type' => 'primary', 'class' => 'mx-4'))),
                    btn(set(array('text' => $lang->goback, 'url' => createLink('user', 'login'), 'class' => 'mx-4 not-open-url')))
                )
            )
        )
    )
);

render('pagebase');
