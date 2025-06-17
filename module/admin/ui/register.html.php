<?php
declare(strict_types=1);
/**
 * The index view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jialiang Zhang <zhangjialiang@chandao.com>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

modalHeader(set::title($this->lang->admin->register->registerTitle));

$checked = $agreeUX == 'true' ? 'checked' : '';

if($bindCommunity)
{
    panel
    (
        setID('main'),
        setClass('flex justify-center'),
        div
        (
            setID('mainContent'),
            setClass('px-1 mt-2 w-full max-w-7xl'),
            div(
                setClass('max-w-7xl h-40'),
                $lang->admin->register->officialWebsite,
                br(),
                span(
                    $bindCommunityMobile
                ),
                div(
                    html("<button class='btn btn-wide btn-primary' id='unBind'>{$lang->admin->register->unBindText}</button>")
                )
            ),
            div(
                $lang->admin->register->join,
                a
                (
                    setID('experience-plan-show'),
                    set('data-size', 'sm'),
                    $lang->admin->register->uxPlanWithBookTitle,
                    set::href(createLink('admin', 'planModal')),
                    set('data-toggle', 'modal')
                ),
                $lang->admin->register->joinDesc,
            ),
            div(
                html("<input type='checkbox' id='agreeUX' " . $checked . "/>")
            )
        )
    );
}
else
{
    div
    (
        setID('main'),
        setClass('flex justify-center'),
        div
        (
            setID('mainContent'),
            setClass('px-1 mt-2 w-full max-w-7xl'),
            formPanel
            (
                set::formID('joinForm'),
                div(
                    setClass('max-w-7xl h-40'),
                ),
                setClass('bg-canvas m-auto mw-auto'),
                set::headingClass('w-96 m-auto'),
                set::submitBtnText($lang->save),
                formRow
                (
                    setClass('w-96 m-auto'),
                    formGroup
                    (
                        set::label($lang->admin->register->mobile),
                        input
                        (
                            set::name('mobile'),
                            setID('mobile-captcha')
                        )
                    )
                ),
                p
                (
                    setID('captchaImageError')
                ),
                p
                (
                    setID('captchaMobileError')
                ),
                formRow
                (
                    setClass('w-96 m-auto'),
                    formGroup
                    (
                        set::label($lang->admin->register->smsCode),
                        input
                        (
                            set::name('code')
                        )
                    ),
                    a
                    (
                        setID('captcha-btn'),
                        setClass('captcha-btn-class'),
                        set('href', 'javascript:;'),
                        $lang->admin->register->sendCode
                    )
                ),
                formRow
                (
                    setClass('w-96 m-auto'),
                    formGroup
                    (
                        checkbox
                        (
                            set::name('agreeUX'),
                            set::value(1)
                        ),
                        span
                        (
                            $lang->admin->register->join,
                            a
                            (
                                setID('experience-plan-show'),
                                set('data-size', 'sm'),
                                $lang->admin->register->uxPlanWithBookTitle,
                                set::href(createLink('admin', 'planModal')),
                                set('data-toggle', 'modal')
                            ),
                            $lang->admin->register->uxPlanStatusTitle
                        )
                    )
                )
            ),
            div(
                setClass('captcha-mobile-sender captch-box'),
                set::style(array('display' => 'none')),
                div
                (
                    setClass('form-group captch-box'),
                    set::style(array('margin-bottom' => 0)),
                    div
                    (
                        set::style(array('padding-right' => 0)),
                        setClass('captcha-wrapper'),
                        div
                        (
                            setClass('image-box')
                        )
                    )
                ),
                div
                (
                    setClass('w-96 m-auto'),
                    input
                    (
                        set::name('captchaImage'),
                        set::placeholder($lang->admin->register->captchaTip)
                    )
                ),
                btn
                (
                    setID('checkMobileSender'),
                    setClass('px-4'),
                    set::type('primary'),
                    $lang->admin->register->sure
                )
            )
        )
    );
    if (strpos($_SERVER['REQUEST_URI'], '_single=1') !== false) {
        render('pagebase');
    }
}