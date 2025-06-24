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
    $backBtn = '';
}
else
{
    $backBtn = div
    (
        setClass('page-title'),
        a
        (
            icon(setClass('icon icon-back')),
            setClass('btn capitalize primary'),
            set::href(helper::createLink('admin')),
            $lang->admin->community->goBack
        ),
        span($lang->admin->community->registerTitle),
    );
}

if(strpos($_SERVER['REQUEST_URI'], '_single=1') !== false)
{
    $skip = div
    (
        a
        (
            setClass('btn capitalize skip-btn'),
            $lang->admin->community->skip,
            set::href(createLink('user', 'login')),
        )
    );
}
else
{
    $skip = '';
}

$welcomeText = $bindCommunity ? $lang->admin->community->welcomeForBound : $lang->admin->community->welcome;

$header[] = div
(
    setClass('max-w-7xl h-32 form-title'),
    div(setClass('main-title text-xl'), $welcomeText),
    div(setClass('sub-title'), icon(setClass('icon icon-diamond')),   $lang->admin->community->advantage1),
    div(setClass('sub-title'), icon(setClass('icon icon-team')),      $lang->admin->community->advantage2),
    div(setClass('sub-title'), icon(setClass('icon icon-statistic')), $lang->admin->community->advantage3),
    div(setClass('sub-title'), icon(setClass('icon icon-manual')),    $lang->admin->community->advantage4),
);

if($bindCommunity)
{
    $backBtn;
    div
    (
        setID('main'),
        setClass('flex justify-center'),
        div
        (
            setID('mainContent'),
            setClass('px-1 mt-2 w-full max-w-7xl'),
            div
            (
                setClass('panel panel-form pt-4 size-sm is-lite'),
                $header,
                div
                (
                    setClass('z-box-container'),
                    div
                    (
                        setClass('z-bind-info'),
                        div
                        (
                            setClass('z-bind-info-image'),
                            img(set::src('static/images/register-logo.png')),
                            div
                            (
                                div(setClass('z-bind-info-website'), html(nl2br($lang->admin->community->officialWebsite))),
                                div(setClass('z-bind-info-mobile'), $bindCommunityMobile)
                            ),
                            button
                            (
                                icon
                                (
                                    setClass('icon icon-unlink')
                                ),
                                setData(array('position' => 'center', 'toggle' => 'modal', 'target' => '#positionModal')),
                                setClass('btn btn-primary z-unbind-btn'),
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
                        )
                    ),
                    div
                    (
                        setClass('z-plan-info'),
                        div
                        (
                            setClass('z-plan-info-box'),
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
                            ),
                            div
                            (
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
                        button
                        (
                            setClass('btn capitalize primary'),
                            $lang->admin->community->goCommunity,
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
    $backBtn;
    div
    (
        setID('main'),
        setClass('flex justify-center'),
        div
        (
            setID('mainContent'),
            setClass('px-1 mt-2 w-full max-w-7xl'),
            div
            (
                setClass('panel panel-form pt-4 size-sm is-lite'),
                $header,
                formPanel
                (
                    set::formID('joinForm'),
                    setClass('bg-canvas m-auto mw-auto'),
                    set::headingClass('w-96 m-auto'),
                    set::submitBtnText($lang->admin->community->registerTitle),
                    set::cancelBtnText(),
                    div(setClass('label-text'), $lang->admin->community->mobile),
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
                                set::placeholder($lang->admin->community->enterMobile)
                            )
                        )
                    ),
                    p(setID('captchaImageError')),
                    p(setID('captchaMobileError')),
                    div
                    (
                        setID('captchaImageLabel'),
                        setClass('label-text'),
                        $lang->admin->community->smsCode,
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
                                setClass('form-agree-ux-text'),
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
                        setClass('w-96 m-auto'),
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
