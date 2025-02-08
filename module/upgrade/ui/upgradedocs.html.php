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

$totalCount = (isset($upgradeDocs['html']) ? count($upgradeDocs['html']) : 0) + (isset($upgradeDocs['doc']) ? count($upgradeDocs['doc']) : 0);
$upgradeTip = sprintf($lang->upgrade->upgradeDocsTip, $totalCount);

$handleNextBtnClick = jsCallback('event')
    ->const('upgradeDocs', $upgradeDocs)
    ->const('upgradingDocsText', $lang->upgrade->upgradingDocs)
    ->const('nextText', $lang->upgrade->next)
    ->do(<<<'JS'
const $btn = $('#upgradeDocsBtn');
if($btn.hasClass('is-finished')) return;

event.preventDefault();
const $progressBar = $('#upgradeDocsProgress').addClass('active').find('.progress-bar');
$btn.attr('disabled', 'disabled').addClass('disabled').removeClass('primary').find('.text').text(upgradingDocsText);
zui.DocApp.migrateDocs(upgradeDocs,
{
    onProgress: (current, total) =>
    {
        $progressBar.css('width', (100 * current / total) + '%');
        $btn.find('.text').text(`${upgradingDocsText} (${current}/${total})`);
    }
}).then(() =>
{
    $btn.removeAttr('disabled').removeClass('disabled').addClass('primary is-finished').find('.text').text(nextText);
    $('#upgradeDocsProgress').removeClass('active')
});
JS);

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
                    span(setClass('hidden as-upgrading-text'), $lang->upgrade->upgradingDocs),
                    span(setClass('hidden as-finish-text'), $lang->upgrade->next),
                    $lang->upgrade->upgradeDocs,
                    on::click($handleNextBtnClick)
                )
            )
        )
    )
);

render('pagebase');
