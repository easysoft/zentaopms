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
    set::id('backupSettingsForm'),
    set::title($lang->instance->backup->operators['settings']),
    set::submitBtnText($lang->save),
    set::actions(array('submit')),
    formRow
    (
        formGroup
        (
            set::name('backupKeepDays'),
            set::width('20px'),
            set::required(true),
            set::label($this->lang->instance->backup->keepDays),
            set::control('input'),
            set::value((int)$instance->backupKeepDays),
            span($this->lang->instance->backup->backupSettingsTips, set::className('text-warning inline-block mt-2'))
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('20px'),
            set::label($lang->instance->restore->enableAutoRestore),
            radioList
            (
                set::name('autoBackup'),
                set::items($lang->instance->backup->autoRestoreOptions),
                set::value($instance->autoBackup),
                set::inline(true)
            )
        )
    ),
    formRow(
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
    formRow(
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
