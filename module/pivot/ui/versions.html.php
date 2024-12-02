<?php
declare(strict_types = 1);
/**
 * The versions view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xin Zhou <zhouxin@chandao.net>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('pivot', $pivot->id);
jsVar('version', $version);
jsVar('group', $groupID);

include_once 'show.html.php';

$versionsTableBody = array();
foreach($versionSpecs as $versionSpec)
{
    $isMarked       = in_array($versionSpec->version, $markedVersions);
    $isBuiltIn      = $builtin == 1;
    $isFirstVersion = $versionSpec->version == 1;
    $isMainVersion  = filter_var($versionSpec->version, FILTER_VALIDATE_INT) !== false;
    $versionsTableBody[] = h::tr
    (
        $version == $versionSpec->version ? setClass('bg-secondary-50') : null,
        on::click("previewVersion"),
        h::td
        (
            setData(array('pivot' => $pivot->id, 'version' => $versionSpec->version, 'group' => $groupID)),
            '#' . $versionSpec->version,
            span
            (
                setData(array('pivot' => $pivot->id, 'version' => $versionSpec->version, 'group' => $groupID)),
                setClass("label ghost size-sm bg-secondary-50 text-secondary-500 rounded-full", array('hidden' => $isMarked || $isFirstVersion || !$isBuiltIn || ($isBuiltIn && !$isMainVersion))),
                $lang->pivot->newVersion
            )
        ),
        h::td
        (
            setData(array('pivot' => $pivot->id, 'version' => $versionSpec->version, 'group' => $groupID)),
            empty($versionSpec->desc) ? $lang->pivot->noDesc : $versionSpec->desc
        )
    );
}

formPanel
(
    setID('pivotVersionPanel'),
    set::title("{$pivot->name} #{$version}"),
    set::shadow(false),
    set::headingClass('h-12'),
    set::bodyClass('pt-0'),
    to::titleSuffix
    (
        div
        (
            label
            (
                to::before
                (
                    icon
                    (
                        setClass('warning-ghost margin-left8'),
                        'help'
                    )
                ),
                set::text($lang->pivot->tipVersions),
                setClass('gray-200-pale')
            )
        )
    ),
    div
    (
        setClass('overflow-y-auto h-44'),
        h::table
        (
            set::className('versionTable table bordered table-hover'),
            h::tr
            (
                h::th($lang->pivot->versionNumber),
                h::th($lang->pivot->desc)
            ),
            $versionsTableBody
        )
    ),
    div
    (
        setID('pivotContent'),
        setClass('flex col gap-4 w-full'),
        $generateData()
    ),
    set::actions(array('submit')),
    set::submitBtnText($lang->pivot->switchTo)
);
