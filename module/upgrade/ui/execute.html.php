<?php
declare(strict_types=1);
/**
 * The execute view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

jsVar('fromVersion', $fromVersion);
jsVar('toVersion', $toVersion);
jsVar('upgradeChanges', $upgradeChanges);

$totalVersions  = count($upgradeVersions);
$completedCount = array_search($toVersion, array_keys($upgradeVersions));
$progress       = $totalVersions ? (int)($completedCount / $totalVersions * 100) : 0;
$progressLabel  = $completedCount . '/' . $totalVersions;

$buildVersions = function() use ($lang, $toVersion, $upgradeVersions)
{
    $editionNames = [];
    foreach(['open', 'biz', 'max', 'ipd'] as $edition)
    {
        $editionNames[$edition] = $edition == 'open' ? $lang->pmsName : $lang->{$edition . 'Name'};
    }

    $versionIndex   = 0;
    $toVersionIndex = array_search($toVersion, array_keys($upgradeVersions)) ?: 0;

    $versions = [];
    foreach($upgradeVersions as $key => $version)
    {
        $class   = $versionIndex < $toVersionIndex ? 'text-success' : 'text-gray-400';
        $icon    = $versionIndex < $toVersionIndex ? 'check-circle' : ($versionIndex == $toVersionIndex ? 'spinner-indicator' : 'clock');
        $edition = is_numeric($key[0]) ? 'open' : substr($key, 0, 3);
        $versions[] = div
        (
            row
            (
                setClass('version-item items-center gap-2' . ($versionIndex < $toVersionIndex ? ' executed' : '')),
                icon
                (
                    setClass("text-xl {$class}"),
                    $icon
                ),
                span
                (
                    $editionNames[$edition] . str_ireplace($edition, '', $version)
                )
            )
        );

        $versionIndex++;
    }
    return $versions;
};

$buildChanges = function() use ($lang, $upgradeChanges)
{
    $changes    = [];
    $bgColors   = ['create' => 'success-pale', 'update' => 'primary-pale', 'delete' => 'danger-pale'];
    $textColors = ['create' => 'text-success', 'update' => 'text-primary', 'delete' => 'text-danger'];
    foreach($upgradeChanges as $key => $change)
    {
        $changes[] = row
        (
            setClass('change-item items-center gap-3'),
            setData(['change' => $change]),
            span
            (
                setClass("label {$bgColors[$change['mode']]} {$textColors[$change['mode']]} px-2.5 py-1"),
                $lang->upgrade->changeModes[$change['mode']]
            ),
            span
            (
                $change['content']
            ),
            $change['type'] == 'sql' ? a
            (
                set::href("javascript:showSQL({$key})"),
                icon
                (
                    setClass('text-gray-400 text-lg'),
                    'fields'
                ),
            ) : null
        );
    }
    return $changes;
};

div
(
    setStyle(['padding' => '3rem 4rem', 'height' => '100vh', 'overflow' => 'hidden']),
    col
    (
        setClass('container rounded-md bg-white gap-2 px-8 py-6 h-full'),
        div
        (
            setClass('text-xl font-medium'),
            $lang->upgrade->execute,
        ),
        div
        (
            setClass('text-warning'),
            $lang->upgrade->upgradingTips
        ),
        row
        (
            setClass('bg-gray-100 gap-2 p-2'),
            setStyle(['max-height' => 'calc(100% - 4rem)']),
            col
            (
                setClass('bg-white rounded-md justify-between gap-4 p-4 w-64 h-full'),
                span
                (
                    setClass('text-lg font-medium'),
                    $lang->upgrade->versionTips
                ),
                col
                (
                    setID('versionsBox'),
                    setClass('gap-4 overflow-x-hidden overflow-y-auto h-full'),
                    $buildVersions
                ),
                col
                (
                    setClass('gap-2'),
                    span
                    (
                        $lang->upgrade->progress
                    ),
                    row
                    (
                        setClass('items-center gap-2'),
                        progressbar
                        (
                            setClass('rounded-full'),
                            setStyle(['height' => '.75rem', 'width' => 'calc(100% - 2rem)']),
                            set::color('rgba(var(--color-success-500-rgb), var(--tw-bg-opacity));'),
                            set::percent($progress)
                        ),
                        span
                        (
                            $progressLabel
                        )
                    )
                )
            ),
            col
            (
                setClass('rounded-md gap-4 bg-white px-6 py-4 w-full h-full'),
                row
                (
                    setClass('items-center justify-between'),
                    span
                    (
                        setClass('text-lg font-medium'),
                        sprintf($lang->upgrade->changeTips, $editionName . str_ireplace($config->edition, '', $upgradeVersions[$toVersion]))
                    ),
                    span
                    (
                        html(sprintf($lang->upgrade->executedChanges, count($upgradeChanges)))
                    )
                ),
                col
                (
                    setID('changesBox'),
                    setClass('gap-4 overflow-x-hidden overflow-y-auto h-full'),
                    $buildChanges
                )
            )
        )
    )
);

render('pagebase');
