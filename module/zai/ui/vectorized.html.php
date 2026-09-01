<?php
/**
 * The zai setting view file of zai module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun<sunhao@chandao.com>
 * @package     zai
 * @link        https://www.zentao.net
 */
namespace zin;

include './sidebar.html.php';

$panelClass = array('mb-4', 'relative');
foreach(array_keys($lang->zai->vectorizedStatusList) as $statusType)
{
    $panelClass[] = $statusType === $status ? "is-status-{$statusType}" : "not-status-{$statusType}";
}
$panelClass[] = ($status === 'syncing' || $status === 'wait') ? 'is-syncing-loop' : 'not-syncing-loop';

$toolbarItems = array();
if($status === 'unavailable')
{
    $toolbarItems[] = setting()->text($lang->zai->addSetting)->type('primary')->url(createLink('zai', 'setting', 'mode=edit'))->toArray();
}

$progressItems = array();
foreach($progressList as $item)
{
    $progressItems[] = wg
    (
        div
        (
            setClass('vectorized-progress row items-center border-t'),
            setData('type', $item->type),
            div
            (
                setClass('vectorized-sync-type border-r pr-4 mr-4 pt-2 w-24 text-right pb-6'),
                $item->text
            ),
            div
            (
                setClass('flex-none'),
                div
                (
                    setClass('vectorized-sync-progress progress overflow-hidden h-4'),
                    setStyle('width', $item->barWidth . 'px'),
                    div
                    (
                        setClass('progress-bar is-synced'),
                        setStyle('width', $item->total ? "{$item->syncedPct}%" : '1px'),
                        setStyle('min-width', '1px')
                    ),
                    div
                    (
                        setClass('progress-bar is-failed danger'),
                        setStyle('width', $item->total ? "{$item->failedPct}%" : '0')
                    )
                ),
                div
                (
                    setClass('text-sm mt-1 flex items-center gap-1 flex-wrap'),
                    div
                    (
                        setClass('vectorized-finished-info flex-none'),
                        $lang->zai->finished . ' ',
                        span(setClass('vectorized-finished-count'), $item->synced)
                    ),
                    div
                    (
                        setClass('vectorized-failed-info pl-2 flex-none', $item->failed ? '' : 'hidden'),
                        $lang->zai->failed . ' ',
                        span(setClass('vectorized-failed-count'), $item->failed)
                    )
                )
            )
        )
    );
}

$alertContent = [];
if($status == 'disabled')    $alertContent[] = p(setClass('vectorized-intro mb-3'), $lang->zai->vectorizedIntro);
if($status == 'unavailable') $alertContent[] = p(setClass('vectorized-intro mb-3'), $lang->zai->vectorizedUnavailableHint);
if($status == 'disabled')    $alertContent[] = form
(
    setClass('not-watch form-horz'),
    set::actions(array('submit')),
    set::submitBtnText($lang->zai->syncActions->enable),
    input(set::type('hidden'), set::name('enable'), set::value('1'))
);
if($toolbarItems) $alertContent[] = toolbar(setClass('vectorized-actions gap-4'), set::items($toolbarItems));
if($status != 'disabled' && $status != 'unavailable' && !empty($pendingEnqueue)) $alertContent[] = div
(
    setID('enqueueResult'),
    setClass('mt-3'),
    setData('auto-enqueue', !empty($pendingEnqueue) ? '1' : '0'),
    !empty($pendingEnqueue) ? button
    (
        setID('continueEnqueueBtn'),
        setClass('btn primary mb-2'),
        on::click('enqueueTargets'),
        $lang->zai->enqueueContinue
    ) : null
);

panel
(
    set::title($lang->zai->vectorized),
    set::size('lg'),
    setClass(implode(' ', $panelClass)),
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
                span(setClass('vectorized-status'), $lang->zai->vectorizedStatusList[$displayStatus])
            ),
            $lastSyncTime ? div
            (
                setClass('vectorized-last-sync-info'),
                text($lang->zai->lastSyncTime . $lang->colon),
                span(setClass('vectorized-last-sync-time'), $lastSyncTime)
            ) : null,
            ($status == 'syncing' || $status == 'wait') ? div
            (
                setClass('text-gray'),
                empty($pendingEnqueue) ? $lang->zai->syncingHint : $lang->zai->enqueueHint
            ) : null,
            $alertContent ? div(setClass('alert-text'), $alertContent) : null,
        )
    ),
    ($status !== 'disabled' && $status !== 'unavailable') ? div
    (
        setClass('vectorized-states border rounded p-4 pb-2 mt-4'),
        div
        (
            setClass('mb-2 row items-center'),
            h4(setClass('flex-none'), $lang->zai->syncProgress),
            div
            (
                setClass('text-gray ml-4'),
                $lang->zai->totalSync . $lang->colon . ' ',
                $lang->zai->finished,
                span(setClass('vectorized-finished-total-count ml-2 mr-4'), (int)$info->syncedCount),
                $lang->zai->failed,
                span(setClass('vectorized-failed-total-count ml-2'), (int)$info->syncFailedCount)
            )
        ),
        div
        (
            setClass('vectorized-progresses'),
            $progressItems
        )
    ) : null
);
