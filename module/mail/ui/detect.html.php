<?php
/**
 * The detect view file of mail module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     mail
 * @link        https://www.zentao.net
 */
declare(strict_types=1);
namespace zin;

formPanel(
    set::labelWidth('11em'),
    set::size('sm'),
    setClass('pt-4'),
    formGroup(
        set::width('1/2'),
        set::label($lang->mail->inputFromEmail),
        inputGroup(
            input(
                set::name('fromAddress'),
                set::value($fromAddress),
                set::required(true),
                set::autofocus(true)
            )
        )
    ),
    set::actions(array('submit')),
    set::actionsClass('w-1/2'),
    set::submitBtnText($lang->mail->nextStep)
);
