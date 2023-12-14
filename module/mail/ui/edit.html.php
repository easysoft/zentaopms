<?php
declare(strict_types=1);
/**
 * The eidt view file of mail module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     mail
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('testBtn', $lang->mail->test);
$formActions = array(
    'submit',
    array(
        'url'   => inlink('reset'),
        'text'  => $lang->mail->reset,
        'class' => 'btn-wide'
    )
);
if(common::hasPriv('mail', 'browse') && !empty($config->mail->async) && !empty($config->global->cron))
{
    $formActions[] = array(
        'url'   => inlink('browse'),
        'text'  => $lang->mail->browse,
        'class' => 'btn-wide'
    );
}

if(!$openssl)
{
    unset($lang->mail->secureList['ssl']);
    unset($lang->mail->secureList['tls']);
    $mailConfig->secure = '';
}

formPanel
(
    set::url(inlink('save')),
    formGroup(
        set::width('1/2'),
        set::label($lang->mail->turnon),
        set::control('radioListInline'),
        set::name('turnon'),
        set::value(1),
        set::items($lang->mail->turnonList)
    ),
    !empty($config->global->cron) ? formGroup(
        set::width('1/2'),
        set::label($lang->mail->async),
        set::control('radioListInline'),
        set::name('async'),
        set::value(zget($config->mail, 'async', 0)),
        set::items($lang->mail->asyncList)
    ) : null,
    formRow
    (
        formGroup(
            set::width('1/4'),
            set::label($lang->mail->fromAddress),
            set::name('fromAddress'),
            set::value($mailConfig->fromAddress)
        ),
        span
        (
            setClass('ml-4 pt-2 flex items-center text-gray'),
            icon('info text-warning mr-1'),
            $lang->mail->addressWhiteList
        )
    ),
    formGroup(
        set::width('1/2'),
        set::label($lang->mail->fromName),
        set::name('fromName'),
        set::value($mailConfig->fromName),
        set::required(true)
    ),
    formGroup(
        set::width('1/2'),
        set::label($lang->mail->domain),
        set::name('domain'),
        set::value($mailConfig->domain)
    ),
    formGroup(
        set::width('1/2'),
        set::label($lang->mail->host),
        set::name('host'),
        set::value($mailConfig->host)
    ),
    formGroup(
        set::width('1/4'),
        set::label($lang->mail->port),
        set::name('port'),
        set::value($mailConfig->port)
    ),
    formRow
    (
        formGroup(
            set::width('1/2'),
            set::label($lang->mail->secure),
            set::control('radioListInline'),
            set::name('secure'),
            set::value($mailConfig->secure),
            set::items($lang->mail->secureList)
        ),
        !$openssl ? span
        (
            setClass('ml-4 pt-2 flex items-center text-gray'),
            icon('info text-warning mr-1'),
            $lang->mail->disableSecure
        ) : null
    ),
    formGroup(
        set::width('1/2'),
        set::label($lang->mail->auth),
        set::control('radioListInline'),
        set::name('auth'),
        set::value($mailConfig->auth),
        set::items($lang->mail->authList)
    ),
    formGroup(
        set::width('1/2'),
        set::label($lang->mail->username),
        set::name('username'),
        set::value($mailConfig->username)
    ),
    formGroup(
        set::width('1/2'),
        set::label($lang->mail->password),
        set::name('password'),
        set::control('password'),
        set::value($mailConfig->password),
        set::placeholder($lang->mail->placeholder->password)
    ),
    formGroup(
        set::width('1/2'),
        set::label($lang->mail->debug),
        set::control('radioListInline'),
        set::name('debug'),
        set::value($mailConfig->debug),
        set::items($lang->mail->debugList)
    ),
    formGroup(
        set::width('1/2'),
        set::label($lang->mail->charset),
        set::control('radioListInline'),
        set::name('charset'),
        set::value($mailConfig->charset),
        set::items($config->charsets[$this->cookie->lang])
    ),
    set::actions($formActions)
);

div
(
    setClass('hidden'),
    div
    (
        setID('hasMail'),
        div
        (
            setClass('flex items-center'),
            icon('check-circle text-success icon-2x'),
            p
            (
                setClass('text-lg font-bold pl-2'),
                $lang->mail->successSaved
            )
        )
    ),
    div
    (
        setID('noMail'),
        div
        (
            setClass('flex items-center'),
            icon('check-circle text-success icon-2x'),
            p
            (
                setClass('text-lg font-bold pl-2'),
                $lang->mail->successSaved
            )
        ),
        div
        (
            setClass('ml-4 pt-2 flex items-center text-gray'),
            icon('info text-warning mr-1'),
            $lang->mail->setForUser
        )
    )
);
