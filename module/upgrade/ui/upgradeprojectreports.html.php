<?php
declare(strict_types=1);
/**
 * The upgradeProjectReports view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
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
            setStyle('width', '600px'),
            set::title(sprintf($lang->upgrade->upgradeProjectReportsTip, count($upgradeReports))),
            set::size('lg'),
            progressBar
            (
                setID('upgradeReportsProgress'),
                set::striped(),
                set::percent(0)
            ),
            div
            (
                setClass('mt-3 py-3 row items-center gap-4 justify-center'),
                btn
                (
                    setID('upgradeReportsBtn'),
                    set::type('primary'),
                    set::url('upgrade', 'upgradeprojectreports', "fromVersion={$fromVersion}&processed=yes"),
                    on::click()->call('startUpgradeReports', jsRaw('event'), $upgradeReports, $lang->upgrade->upgradingProjectReports, $lang->upgrade->next),
                    span(setClass('hidden as-upgrading-text'), $lang->upgrade->upgradingProjectReports),
                    span(setClass('hidden as-finish-text'), $lang->upgrade->next),
                    $lang->upgrade->upgradeProjectReports
                )
            )
        )
    )
);
render('pagebase');
