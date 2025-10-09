<?php
/**
 * The zai setting view file of zai module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun<sunhao@chandao.com>
 * @package     zai
 * @link        https://www.zentao.net
 */
namespace zin;

include './sidebar.html.php';

$toolbarItems = array();
$toolbarItems[] = setting()->text($lang->zai->addSetting)->className('hide-not-unavailable')->type('primary')->url(createLink('zai', 'setting', 'mode=edit'))->toArray();
$toolbarItems[] = setting()->text($lang->zai->syncActions->enable)->className('hide-not-disabled')->type('primary')->set('zui-command', 'enable')->toArray();
$toolbarItems[] = setting()->text($lang->zai->syncActions->startSync)->className('hide-not-wait')->type('primary')->set('zui-command', 'startSync')->toArray();
$toolbarItems[] = setting()->text($lang->zai->syncActions->resync)->className('hide-not-synced')->type('primary')->set('zui-command', 'resync')->toArray();
$toolbarItems[] = setting()->text($lang->zai->syncActions->resetSync)->className('show-on-failed-success-paused')->type('danger-pale')->set('zui-command', 'resetSync')->toArray();
$toolbarItems[] = setting()->text($lang->zai->syncActions->pauseSync)->className('hide-not-syncing')->type('primary')->set('zui-command', 'pauseSync')->toArray();
$toolbarItems[] = setting()->text($lang->zai->syncActions->resumeSync)->className('hide-not-paused')->type('primary')->set('zui-command', 'resumeSync')->toArray();

$progressItems = array();
foreach($syncTypes as $type => $text)
{
    $progressItems[] = wg
    (
        div
        (
            setClass('vectorized-progress row items-center border-t'),
            setData('type', $type),
            div
            (
                setClass('vectorized-sync-type border-r pr-4 mr-4 pt-2 w-24 text-right pb-6'),
                $text
            ),
            div
            (
                setClass('flex-none'),
                html('<div class="vectorized-sync-progress progress progress-striped overflow-hidden h-4"><div class="progress-bar is-synced" style="width: 100%; min-width: 1px"></div><div class="progress-bar is-failed danger" style="width: 0%"></div></div>'),
                div
                (
                    setClass('text-sm mt-1 flex items-center gap-1'),
                    div
                    (
                        setClass('vectorized-finished-info flex-none'),
                        $lang->zai->finished . ' ',
                        span(setClass('vectorized-finished-count'))
                    ),
                    div
                    (
                        setClass('vectorized-failed-info pl-2 flex-none'),
                        $lang->zai->failed . ' ',
                        span(setClass('vectorized-failed-count'))
                    ),
                    icon('spinner-indicator spin mx-2 text-gray vectorized-loading-icon')
                )
            )
        )
    );
}

panel
(
    set::title($lang->zai->vectorized),
    set::size('lg'),
    setClass('vectorized-panel mb-4 load-indicator loading relative'),
    zui::vectorizedPanel
    (
        set((array)$info),
        set::langData($lang->zai->vectorizedPanelLang),
        set::zaiSetting($zaiSetting)
    ),
    on::init()->call('updateVectorizedState', jsRaw('$element')),
    div
    (
        setClass('vectorized-alert alert bg-gray-pale'),
        div
        (
            setClass('alert-content p-2'),
            h4
            (
                setClass('alert-heading flex items-center gap-1'),
                text($lang->zai->vectorizedStatus . $lang->colon),
                span(setClass('vectorized-status'), $lang->zai->vectorizedStatusList[$info->status]),
                icon('spinner-indicator spin text-gray hide-not-syncing-loop'),
                icon('check-circle text-lg text-success hide-not-synced hide-on-synced-failed')
            ),
            div
            (
                setClass('vectorized-last-sync-info hidden'),
                text($lang->zai->lastSyncTime . $lang->colon),
                span(setClass('vectorized-last-sync-time')),
                span(setClass('vectorized-synced-with-failed hidden ml-2 text-danger'), $lang->zai->syncedWithFailedHint)
            ),
            div
            (
                setClass('hide-not-syncing text-gray'),
                $lang->zai->syncingHint
            ),
            div
            (
                setClass('alert-text'),
                p(setClass('vectorized-intro mb-3 hide-not-disabled'), $lang->zai->vectorizedIntro),
                p(setClass('vectorized-intro mb-3 hide-not-unavailable'), $lang->zai->vectorizedUnavailableHint),
                toolbar(setClass('vectorized-actions gap-4'), set::items($toolbarItems))
            )
        )
    ),
    div
    (
        setClass('vectorized-states hide-on-disabled hide-on-unavailable border rounded p-4 pb-2 mt-4'),
        div
        (
            setClass('mb-2 row items-center'),
            h4(setClass('flex-none'), $lang->zai->syncProgress),
            div
            (
                setClass('text-gray ml-4'),
                $lang->zai->totalSync . $lang->colon . ' ',
                $lang->zai->finished,
                span(setClass('vectorized-finished-total-count ml-2 mr-4'), 0),
                $lang->zai->failed,
                span(setClass('vectorized-failed-total-count ml-2'), 0)
            )
        ),
        div
        (
            setClass('vectorized-progresses'),
            $progressItems
        )
    )
);
