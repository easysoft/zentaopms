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
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->settingModule),
            set::name('module'),
            set::value($settings['module']),
            set::placeholder('M')
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->settingScene),
            set::name('scene'),
            set::value($settings['scene']),
            set::placeholder('S')
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testcase->settingCase),
        set::name('case'),
        set::value($settings['case']),
        set::placeholder('C')
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->settingPri),
            set::name('pri'),
            set::value($settings['pri']),
            set::placeholder('P')
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testcase->settingGroup),
            set::name('group'),
            set::value($settings['group']),
            set::placeholder('G')
        )
    ),
    set::actions(array('submit')),
    set::submitBtnText($lang->export)
);

render('modalDialog');
