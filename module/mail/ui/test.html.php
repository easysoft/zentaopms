<?php
declare(strict_types=1);
/**
 * The test view file of mail module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     mail
 * @link        https://www.zentao.net
 */
namespace zin;

$mta = $config->mail->mta;
formPanel
(
    modalHeader
    (
        set::entityText($lang->mail->common),
        to::suffix(span
            (
                setClass('ml-2 flex items-center text-gray'),
                icon('info text-warning mr-1'),
                $lang->mail->sendmailTips
            )
        )
    ),
    set::url(inlink('test')),
    formGroup(
        set::width('1/3'),
        setClass('mx-4'),
        set::control('picker'),
        set::name('to'),
        set::value($app->user->account),
        set::items($users)
    ),
    set::actionsClass('w-1/3'),
    set::actions(array(
        array(
            'text'    => $lang->mail->test,
            'type'    => 'primary',
            'onclick' => 'window.sendTest()'
        ),
        array(
            'url'   => inlink(($mta == 'sendcloud' || $mta == 'ztcloud') ? $mta : 'edit'),
            'text'  => $lang->mail->edit,
            'class' => 'btn-wide'
        )
    )),
    div(setID('resultWin'))
);
