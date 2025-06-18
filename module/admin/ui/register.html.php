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

use function Symfony\Component\String\b;

set::zui(true);

$checked = $agreeUX == 'true' ? 'checked' : '';

if (strpos($_SERVER['REQUEST_URI'], '_single=1') !== false) {
    $backBtn = '';
}
else
{
    $backBtn = div(
        setClass('page-title'),
        button(
            icon
            (
                setClass('icon icon-rocket')
            ),
            setClass('btn capitalize primary'),
            on::click('goBack'),
            $lang->admin->register->goBack
        ),
        span($lang->admin->register->registerTitle),
    );
}

if($bindCommunity)
{
    div
    (
        $backBtn,
        setID('main'),
        setClass('flex justify-center'),
        div
        (
            setID('mainContent'),
            setClass('px-1 mt-2 w-full max-w-7xl'),
            div(
                setClass('panel panel-form pt-4 size-sm is-lite'),
                div(
                    setClass('max-w-7xl h-32 form-title'),
                    div(
                        setClass('main-title text-xl'),
                        $lang->admin->register->welcome,
                    ),
                    div(setClass('sub-title'), icon(setClass('icon icon-rocket')), $lang->admin->register->advantage1),
                    div(setClass('sub-title'), icon(setClass('icon icon-rocket')), $lang->admin->register->advantage2),
                    div(setClass('sub-title'), icon(setClass('icon icon-rocket')), $lang->admin->register->advantage3),
                    div(setClass('sub-title'), icon(setClass('icon icon-rocket')), $lang->admin->register->advantage4),
                ),
                div(
                    setClass('z-box-container'),
                    div(
                        setClass('z-bind-info'),
                        div(
                            setClass('z-bind-info-image'),
                            img(
                                set::src('static/images/register-logo.png')
                            ),
                            div(
                                div(
                                    setClass('z-bind-info-website'),
                                    $lang->admin->register->officialWebsite,
                                ),
                                div(
                                    setClass('z-bind-info-mobile'),
                                    $bindCommunityMobile
                                )
                            ),
                            button(
                                icon
                                (
                                    setClass('icon icon-rocket')
                                ),
                                setClass('btn btn-primary z-unbind-btn'),
                                setID('unBind'),
                                $lang->admin->register->unBindText,
                                on::click()->call('unBind'),
                            )
                        )
                    ),
                    div(
                        setClass('z-plan-info'),
                        div(
                            setClass('z-plan-info-box'),
                            span(
                                $lang->admin->register->join,
                            ),
                            a
                            (
                                setID('experience-plan-show'),
                                set('data-size', 'sm'),
                                $lang->admin->register->uxPlanWithBookTitle,
                                set::href(createLink('admin', 'planModal')),
                                set('data-toggle', 'modal')
                            ),
                            span(
                                $lang->admin->register->joinDesc,
                            ),
                            div(
                                setClass('z-switch'),
                                switcher
                                (
                                    setClass('z-switch-toggle'),
                                    setID('agreeUX'),
                                    set('checked', $checked),
                                    on::change()->call('changeAgreeUX', '#agreeUX'),
                                )
                            )
                        ),
                    ),
                    div
                    (
                        setClass('z-footer-btn'),
                        button(
                            setClass('btn capitalize primary'),
                            $lang->admin->register->goCommunity,
                            on::click()->call('goCommunity', $config->admin->register->community),
                        )
                    )
                )
            )
        )
    );
}
else
{
    div
    (
        $backBtn,
        setID('main'),
        setClass('flex justify-center'),
        div
        (
            setID('mainContent'),
            setClass('px-1 mt-2 w-full max-w-7xl'),
            div(
                setClass('panel panel-form pt-4 size-sm is-lite'),
                div(
                    setClass('max-w-7xl h-32 form-title'),
                    div(
                        setClass('main-title text-xl'),
                        $lang->admin->register->welcome,
                    ),
                    div(setClass('sub-title'), icon(setClass('icon icon-rocket')), $lang->admin->register->advantage1),
                    div(setClass('sub-title'), icon(setClass('icon icon-rocket')), $lang->admin->register->advantage2),
                    div(setClass('sub-title'), icon(setClass('icon icon-rocket')), $lang->admin->register->advantage3),
                    div(setClass('sub-title'), icon(setClass('icon icon-rocket')), $lang->admin->register->advantage4),
                ),
                formPanel
                (
                    set::formID('joinForm'),
                    setClass('bg-canvas m-auto mw-auto'),
                    set::headingClass('w-96 m-auto'),
                    set::submitBtnText($lang->admin->register->registerTitle),
                    div(
                        setClass('label-text'),
                        $lang->admin->register->mobile,
                    ),
                    formRow
                    (
                        setClass('m-auto no-label-mobile'),
                        formGroup
                        (
                            setClass('no-label'),
                            input
                            (
                                setClass('no-label-input'),
                                set::name('mobile'),
                                setID('mobile-captcha'),
                                set::placeholder($lang->admin->register->enterMobile)
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
                    div(
                        setID('captchaImageLabel'),
                        setClass('label-text'),
                        $lang->admin->register->smsCode,
                    ),
                    formRow
                    (
                        setClass('m-auto no-label-code'),
                        formGroup
                        (
                            setClass('no-label'),
                            input
                            (
                                setClass('no-label-input'),
                                set::name('code'),
                                set::placeholder($lang->admin->register->enterCode)
                            )
                        ),
                        a
                        (
                            setID('captcha-btn'),
                            setClass('captcha-btn-class'),
                            set('href', 'javascript:;'),
                            $lang->admin->register->sendCode,
                            on::click()->call('showCaptcha'),
                        )
                    ),
                    formRow
                    (
                        setClass('m-auto form-agree-ux'),
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
                    setClass('captcha-mobile-sender captcha-box'),
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
                                setClass('image-box'),
                                on::init()->call('getCaptchaContent', ".image-box"),
                                on::click()->call('getCaptchaContent', ".image-box"),
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
                        ),
                        div
                        (
                            setID('checkMobileSenderID'),
                            setClass('px-4'),
                            set::type('primary'),
                            $lang->admin->register->sure,
                            on::click()->call('checkMobileSender', '#checkMobileSenderID'),
                        )

                    )
                )
            )
        )
    );
    if (strpos($_SERVER['REQUEST_URI'], '_single=1') !== false) {
        render('pagebase');
    }
}