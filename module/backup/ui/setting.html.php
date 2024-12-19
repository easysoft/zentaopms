<?php
declare(strict_types=1);
/**
 * The setting view file of backup module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     backup
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title(''),
    set::entityText($lang->backup->setting)
);

$this->app->loadLang('instance');
if(!empty($error))
{
    html($error);
}
else
{
    formPanel
    (
        set::submitBtnText($lang->save),
        !$this->config->inQuickon ? formGroup
        (
            checkList
            (
                set::name('setting[]'),
                set::items($lang->backup->settingList),
                set::value(isset($config->backup->setting) ? $config->backup->setting : '')
            )
        ) : formRow
        (
            set::label(''),
            span
            (
                icon('info text-warning mr-2'),
                $lang->backup->notice->settingsInQuickon
            )
        ),
        !$this->config->inQuickon ? formGroup
        (
            inputGroup
            (
                $lang->backup->settingDir,
                input
                (
                    set::name('settingDir'),
                    set::value(!empty($config->backup->settingDir) ? $config->backup->settingDir : $this->app->getTmpRoot() . 'backup/')
                )
            )
        ) : null,
        common::hasPriv('backup', 'change') ?
        formRow
        (
            formGroup
            (
                set::name('holdDays'),
                set::width('20px'),
                set::required(true),
                set::label($lang->backup->change),
                set::value($config->backup->holdDays)
            )
        ) : null,
        ($this->config->inQuickon && !empty($instance->id)) ? formRow
        (
            formGroup
            (
                set::width('20px'),
                set::label($lang->instance->restore->enableAutoRestore),
                radioList
                (
                    set::name('autoBackup'),
                    set::items($lang->instance->backup->autoRestoreOptions),
                    set::value(isset($instance->autoBackup) ? $instance->autoBackup : 0),
                    set::inline(true)
                )
            )
        )  : null,
        ($this->config->inQuickon && !empty($instance->id)) ? formRow
        (
            formGroup
            (
                set::name('backupTime'),
                set::width('20px'),
                set::required(true),
                set::class('backup-settings'),
                set::label($lang->instance->backup->backupTime),
                set::control(array('control' => 'time')),
                set::value(isset($backupSettings->backupTime) ? $backupSettings->backupTime : '01:00')
            )
        ) : null,
        ($this->config->inQuickon && !empty($instance->id)) ? formRow
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
                set::value('')
            )
        ) : null
    );
}

/* ====== Render page ====== */
render();
