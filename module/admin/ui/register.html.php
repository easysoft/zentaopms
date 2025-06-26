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

jsVar('reSendText', $lang->admin->community->reSend);

$checked = $agreeUX == 'true' ? 'checked' : '';

if(strpos($_SERVER['REQUEST_URI'], '_single=1') !== false)
{
    set::zui(true);
    $skip = div
    (
        a
        (
            setClass('btn capitalize skip-btn font-normal'),
            $lang->admin->community->skip,
            set::href(createLink('index')),
        )
    );
}
else
{
    $skip = '';
}

$welcomeText = $bindCommunity ? $lang->admin->community->welcomeForBound : $lang->admin->community->welcome;

if($bindCommunity)
{
    div
    (
        div
        (
            setClass('panel pt-4 size-sm is-lite z-box-container'),
            div
            (
                setClass('main-title z-main-title'),
                $welcomeText . $bindCommunityMobile,
                button
                (
                    setData(array('position' => 'center', 'toggle' => 'modal', 'target' => '#positionModal')),
                    setID('unBind'),
                    $lang->admin->community->unBindText,
                ),
                div
                (
                    setID('positionModal'),
                    setClass('modal'),
                    div
                    (
                        setClass('modal-dialog shadow'),
                        div
                        (
                            setClass('modal-content'),
                            div
                            (
                                setClass('modal-body'),
                                p(setClass('unbind-modal-title'),   $lang->admin->community->unbindTitle),
                                p(setClass('unbind-modal-content'), $lang->admin->community->unbindContent)
                            ),
                            div
                            (
                                setClass('modal-footer'),
                                button
                                (
                                    setClass('btn'),
                                    set::type('button'),
                                    setData(array('dismiss' => 'modal')),
                                    $lang->admin->community->cancelButton
                                ),
                                button
                                (
                                    setClass('btn primary'),
                                    set::type('button'),
                                    $lang->admin->community->unbindButton,
                                    on::click()->call('unBind')
                                )
                            )
                        )
                    )
                )
            ),
            div
            (
                setClass('z-plan-info-box'),
                div
                (
                    setClass('z-switch'),
                    checkbox
                    (
                        setClass('z-switch-toggle'),
                        setID('agreeUX'),
                        set('checked', $checked),
                        on::change()->call('changeAgreeUX', '#agreeUX'),
                    )
                ),
                div
                (
                    setClass('z-plan-info-box-text'),
                    span($lang->admin->community->join),
                    a
                    (
                        setID('experience-plan-show'),
                        set('data-size', 'lg'),
                        $lang->admin->community->uxPlanWithBookTitle,
                        set::href(createLink('admin', 'planModal')),
                        set('data-toggle', 'modal')
                    ),
                    span($lang->admin->community->joinDesc)
                )
            ),
            div
            (
                setClass('z-footer-btn'),
                button
                (
                    setClass('btn capitalize primary'),
                    $lang->admin->community->goCommunity,
                    on::click()->call('goCommunity', $config->admin->register->community),
                ),
                a
                (
                    setClass('btn capitalize z-show-gift'),
                    set('data-size', 'sm'),
                    $lang->admin->community->giftPackage,
                    set::href(createLink('admin', 'giftPackage')),
                    set('data-toggle', 'modal')
                ),
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
            setID('ubBindMainContent'),
            setClass('px-1 mt-2 w-full max-w-7xl'),
            div
            (
                setClass('panel panel-form pt-4 size-sm is-lite panel-form-div'),
                setClass('max-w-7xl h-16 form-title bg-canvas'),
                div
                (setClass('main-title text-lg'), $welcomeText),
                formPanel
                (
                    set::formID('joinForm'),
                    setClass('bg-canvas m-auto mw-auto font-normal'),
                    set::headingClass('w-96 m-auto'),
                    set::submitBtnText($lang->admin->community->registerTitle),
                    set::cancelBtnText(),
                    div(
                        setClass('label-text-mobile'),
                        div(
                            setClass('label-text font-normal'),
                            $lang->admin->community->mobile
                        ),
                        formRow
                        (
                            setClass('no-label-mobile font-normal'),
                            formGroup
                            (
                                setClass('no-label'),
                                input
                                (
                                    setClass('no-label-input'),
                                    set::name('mobile'),
                                    setID('mobile-captcha'),
                                    set::placeholder($lang->admin->community->enterMobile)
                                )
                            )
                        )
                    ),
                    div
                    (
                        setClass('label-text-code'),
                        div(
                            setID('captchaImageLabel'),
                            setClass('label-text font-normal'),
                            $lang->admin->community->smsCode
                        ),
                        formRow
                        (
                            setClass('no-label-code font-normal'),
                            formGroup
                            (
                                setClass('no-label'),
                                input
                                (
                                    setClass('no-label-input'),
                                    set::name('code'),
                                    set::placeholder($lang->admin->community->enterCode)
                                )
                            ),
                            a
                            (
                                setID('captcha-btn'),
                                setClass('captcha-btn-class'),
                                set('href', 'javascript:;'),
                                $lang->admin->community->sendCode,
                                on::click()->call('showCaptcha'),
                            )
                        )
                    ),
                    formRow
                    (
                        setClass('form-agree-ux'),
                        formGroup
                        (
                            checkbox
                            (
                                set::name('agreeUX'),
                                set::checked(true)
                            ),
                            span
                            (
                                setClass('form-agree-ux-text font-normal'),
                                $lang->admin->community->join,
                                a
                                (
                                    setID('experience-plan-show'),
                                    set('data-size', 'lg'),
                                    $lang->admin->community->uxPlanWithBookTitle,
                                    set::href(createLink('admin', 'planModal')),
                                    set('data-toggle', 'modal')
                                ),
                                $lang->admin->community->uxPlanStatusTitle
                            )
                        )
                    )
                ),
                $skip,
                div
                (
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
                        setClass('w-96 m-auto font-normal'),
                        input
                        (
                            set::name('captchaImage'),
                            set::placeholder($lang->admin->community->captchaTip)
                        ),
                        div
                        (
                            setID('checkMobileSenderID'),
                            setClass('px-4'),
                            set::type('primary'),
                            html(nl2br($lang->admin->community->sure)),
                            on::click()->call('checkMobileSender', '#checkMobileSenderID'),
                        )
                    )
                )
            )
        )
    );
    if(strpos($_SERVER['REQUEST_URI'], '_single=1') !== false) render('pagebase');
}
