<?php
declare(strict_types=1);
/**
 * The exportxmid view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($lang->testcase->exportXmind);

formPanel
(
    set::target('_self'),
    on::submit('setDownloading'),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testcase->product),
        set::name('product'),
        set::value($productName),
        set::disabled(true)
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testcase->module),
        picker
        (
            set::name('imodule'),
            set::control('picker'),
            set::value($moduleID),
            set::items($moduleOptionMenu)
        )
    ),
    formRowGroup
    (
        set::title($lang->testcase->xmindExportSetting),
        to::suffix
        (
            icon
            (
                'help',
                setClass('text-gray pl-1'),
                toggle::tooltip(array('title' => $lang->testcase->xmindSettingTip))
            )
        )
    ),
);

render('modalDialog');
