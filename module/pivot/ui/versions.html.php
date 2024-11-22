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
        h::td('#' . $versionSpec->version),
        h::td($versionSpec->desc),
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
        icon
        (
            setClass('cursor-pointer text-warning'),
            setData(array('toggle' => 'tooltip', 'title' => $lang->pivot->tipVersions, 'placement' => 'right', 'className' => 'text-wraning border border-light', 'type' => 'white')),
            'help'
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
