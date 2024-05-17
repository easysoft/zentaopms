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
                set::name('setting'),
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
        common::hasPriv('backup', 'change') ? formGroup
        (
            inputGroup
            (
                $lang->backup->change,
                input
                (
                    set::name('holdDays'),
                    set::value($config->backup->holdDays)
                )
            )
        ) : null
    );
}

/* ====== Render page ====== */
render();
