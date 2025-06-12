<?php
declare(strict_types=1);
/**
 * The index view file of officialwebsite module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jialiang Zhang <zhangjialiang@chandao.com>
 * @package     officialwebsite
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

modalHeader(set::title('加入禅道社区'));

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
                    set::label('手机号'),
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
                    set::label('短信验证码'),
                    input
                    (
                        set::name('captcha')
                    )
                ),
                a
                (
                    setID('captcha-btn'),
                    setClass('captcha-btn-class'),
                    set('href', 'javascript:;'),
                    '获取验证码'
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
                        '加入',
                        a
                        (
                            setID('experience-plan-show'),
                            set('data-size', 'sm'),
                            '《用户体验计划》',
                            set::href(createLink('officialwebsite', 'planModal')),
                            set('data-toggle', 'modal')
                        ),
                        '帮助我们...'
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
                    set::placeholder('请输入图形验证码')
                )
            ),
            btn
            (
                setID('checkMobileSender'),
                setClass('px-4'),
                set::type('primary'),
                '确定'
            )
        )
    )
);

if (strpos($_SERVER['REQUEST_URI'], '_single=1') !== false) {
    render('pagebase');
}