<?php
declare(strict_types=1);
/**
 * The browser view file of message module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     message
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->message->setting),
    set::formClass('border-0'),
    set::actions(array('submit')),
    formRow
    (
        formGroup
        (
            set::label($lang->message->browserSetting->turnon),
            radioList
            (
                set::inline(true),
                set::name('turnon'),
                set::items($lang->message->browserSetting->turnonList),
                set::value($browserConfig->turnon)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->message->browserSetting->pollTime),
            set::name('pollTime'),
            set::value($browserConfig->pollTime)
        ),
        formGroup
        (
            setClass('content-center ml-2'),
            set::width('1/2'),
            $lang->message->browserSetting->pollTimePlaceholder
        )
    )
);

render();

