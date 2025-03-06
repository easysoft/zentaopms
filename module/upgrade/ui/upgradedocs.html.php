<?php
declare(strict_types=1);
/**
 * The upgradeDocs view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@chandao.com>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$totalCount = (isset($upgradeDocs['html']) ? count($upgradeDocs['html']) : 0) + (isset($upgradeDocs['doc']) ? count($upgradeDocs['doc']) : 0) + (isset($upgradeDocs['wiki']) ? count($upgradeDocs['wiki']) : 0);
$upgradeTip = sprintf($lang->upgrade->upgradeDocsTip, $totalCount);

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
            set::title($upgradeTip),
            set::size('lg'),
            progressBar
            (
                setID('upgradeDocsProgress'),
                set::striped(),
                set::percent(0)
            ),
            div
            (
                setClass('mt-3 py-3 row items-center gap-4 justify-center'),
                btn
                (
                    setID('upgradeDocsBtn'),
                    set::type('primary'),
                    set::url('upgrade', 'upgradeDocs', "fromVersion={$fromVersion}&processed=yes"),
                    on::click()->call('startUpgradeDocs', jsRaw('event'), $upgradeDocs, $lang->upgrade->upgradingDocs, $lang->upgrade->next),
                    span(setClass('hidden as-upgrading-text'), $lang->upgrade->upgradingDocs),
                    span(setClass('hidden as-finish-text'), $lang->upgrade->next),
                    $lang->upgrade->upgradeDocs
                )
            )
        )
    )
);

render('pagebase');
