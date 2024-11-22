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
include_once 'show.html.php';

$versionsTableBody = array();
foreach($versionSpecs as $versionSpec)
{
    $versionsTableBody[] = h::tr
    (
        h::td
        (
            h::a
            (
                '#' . $versionSpec->version,
                set::href("javascript:;"),
                on::click("switchVersion"),
                setData('pivot', $pivot->id),
                setData('version', $versionSpec->version),
                setData('group', $groupID)
            )
        ),
        h::td($versionSpec->desc)
    );
}

panel
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
    h::table
    (
        set::className('versionTable table bordered'),
        h::tr
        (
            h::th($lang->pivot->versionNumber),
            h::th($lang->pivot->desc)
        ),
        $versionsTableBody
    ),
    div
    (
        setID('pivotContent'),
        setClass('flex col gap-4 w-full'),
        $generateData()
    )
);
