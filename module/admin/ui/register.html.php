<?php
declare(strict_types=1);
/**
 * The register view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

$canBind = hasPriv('admin', 'bind');

$account = zget($config->global, 'community', '');
if($account == 'na') $account = '';

$buildCaptchRow = function($type) : node
{
    global $lang;
    return formRow
    (
        formGroup
        (
            setClass('w-1/2'),
            set::label($lang->user->$type),
            set::required(true),
            inputGroup
            (
                input
                (
                    set::name($type)
                ),
                span
                (
                    setClass('input-group-btn'),
                    button
                    (
                        on::click("sendCaptcha(event, '{$type}')"),
                        setClass('btn'),
                        set(array('data-url' => inlink('ajaxSendCode', "type={$type}"))),
                        $lang->admin->getCaptcha
                    )
                )
            )
        ),
        formGroup
        (
            setClass('w-1/2'),
            set::label($lang->admin->captcha),
            set::name("{$type}Code"),
            set::required(true)
        )
    );
};

$buildHidden = function($sn, $siteName): array
{
    $items[] = input
    (
        set::type('hidden'),
        set::name('sn'),
        set::value($sn)
    );
    $items[] = input
    (
        set::type('hidden'),
        set::name($siteName),
        set::value(common::getSysURL())
    );
    return $items;
};

div
(
    setClass('flex'),
    col
    (
        setClass($canBind ? 'pr-2 w-1/2' : 'w-full'),
        formPanel
        (
            setClass('w-full'),
            set::actions(array('submit')),
            set::title($lang->admin->registerNotice->common),
            set::submitBtnText($lang->admin->registerNotice->submit),
            formGroup
            (
                set::label($lang->user->account),
                set::name('account'),
                set::placeholder($lang->admin->registerNotice->lblAccount),
                set::required(true)
            ),
            formGroup
            (
                set::label($lang->user->realname),
                set::name('realname'),
                set::required(true)
            ),
            formGroup
            (
                set::label($lang->user->company),
                set::name('company'),
                set::value($register->company),
                set::required(true)
            ),
            empty($config->isINT) ? $buildCaptchRow('mobile') : null,
            $buildCaptchRow('email'),
            formGroup
            (
                set::label($lang->user->password),
                set::control('password'),
                set::name('password1'),
                set::placeholder($lang->admin->registerNotice->lblPasswd),
                set::required(true)
            ),
            formGroup
            (
                set::label($lang->user->password2),
                set::control('password'),
                set::name('password2'),
                set::required(true)
            ),
            $buildHidden($sn, 'bindSite')
        )
    ),
    $canBind ? col
    (
        setClass('pl-2 w-1/2'),
        formPanel
        (
            setClass('w-full'),
            set::actions(array('submit')),
            set::title($lang->admin->registerNotice->bind),
            set::url(inlink('bind', "from={$from}")),
            set::submitBtnText($lang->admin->bind->submit),
            formGroup
            (
                setClass('w-1/2'),
                set::label($lang->user->account),
                set::name('account'),
                set::value($account),
                set::required(true)
            ),
            formGroup
            (
                setClass('w-1/2'),
                set::label($lang->user->password),
                set::control('password'),
                set::name('password'),
                set::required(true)
            ),
            $buildHidden($sn, 'site')
        )
    ) : null
);

render();
