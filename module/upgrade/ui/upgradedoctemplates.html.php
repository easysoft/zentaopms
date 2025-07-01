<?php
declare(strict_types=1);
/**
 * The upgradeDocTemplates view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@chandao.com>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);
div
(
    setID('main'),
    setClass('flex'),
    div
    (
        setID('mainContent'),
        setClass('mx-auto'),
        panel
        (
            setStyle('width', '800px'),
            set::title($lang->upgrade->upgradeDocTemplatesTip),
            set::size('lg'),
            progressBar
            (
                setID('upgradeDocTemplatesProgress'),
                set::striped(),
                set::percent(0)
            ),
            div
            (
                setClass('mt-3 py-3 row items-center gap-4 justify-center'),
                btn
                (
                    setID('upgradeDocTemplatesBtn'),
                    set::type('primary'),
                    set::url('upgrade', 'upgradeDocTemplates', "fromVersion={$fromVersion}&processed=yes"),
                    on::click()->call('startUpgradeDocTemplates', jsRaw('event'), $upgradeDocTemplates, $lang->upgrade->upgradingDocTemplates, $lang->upgrade->next),
                    span(setClass('hidden as-upgrading-text'), $lang->upgrade->upgradingDocTemplates),
                    span(setClass('hidden as-finish-text'), $lang->upgrade->next),
                    $lang->upgrade->upgradeDocTemplates
                )
            )
        )
    )
);

render('pagebase');
