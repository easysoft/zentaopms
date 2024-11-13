<?php
declare(strict_types=1);
/**
 * The yyy view file of xxx module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xiaojian Zhai <zhaixj@easycorp.ltd>
 * @package     instance
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('automaticBackupForm'),
    set::title($lang->instance->backup->operators['auto']),
    set::submitBtnText($lang->save),
    set::actions(array('submit')),
    formRow
    (
        formGroup
        (
            set::name('autoBackup'),
            set::width('500px'),
            set::control(array('control' => 'checkbox', 'value' => '1', 'text' => $lang->instance->restore->enableAutoRestore, 'checked' => $backupSettings->autoBackup ? 'true' : '')),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('backupTime'),
            set::width('20px'),
            set::required(true),
            set::class('backup-settings'),
            set::label($lang->instance->backup->backupTime),
            set::control(array('control' => 'time')),
            set::value($backupSettings->backupTime)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('backupCycle'),
            set::width('20px'),
            set::required(true),
            set::control('picker'),
            set::class('backup-settings'),
            set::label($lang->instance->backup->cycleDays),
            set::items($lang->instance->backup->cycleList),
            set::value($backupSettings->cycleDays)
        )
    )
);
