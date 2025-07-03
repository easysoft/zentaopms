<?php
declare(strict_types=1);
/**
 * The backupview view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Li Yang<liyang@chandao.com>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;
$backupDetail = zget($backup, 'backup_details', array());

$backupList = array();
if(!empty($backupDetail) && empty($backup->message))
{
    foreach($backupDetail as $type => $infoList)
    {
        if(!in_array($type, array('db', 'volume'))) continue;
        foreach($infoList as $info)
        {
            $size = $type == 'db' ? zget($info, 'size', 0) : zget($info, 'total_bytes', 0);
            $backupList[] = h::tr
            (
                h::td(zget($lang->system->backup->backupTypeList, $type)),
                h::td($type == 'db' ? zget($info, 'db_name', '') : zget($info, 'pvc_name', '')),
                h::td(round($size / 1024 / 1024, 2) == 0 ? $size . ' B' : round($size / 1024 / 1024, 2) . ' MB'),
                h::td(zget($lang->system->backup->statusList, $info->status))
            );
        }
    }
}

detailHeader
(
    to::prefix(''),
    to::title
    (
        entityLabel
        (
            set::level(1),
            set::text($title)
        )
    )
);

div
(
    !empty($backup->message) ? sectionList(div(setClass('w-full text-center pt-10'), sprintf($lang->system->backup->error->backupFailNotice, $backup->message))) : sectionList
    (
        empty($backupList) ? div(setClass('w-full text-center pt-10'), $lang->noData) : div
        (
            setClass('flex-none'),
            h::table
            (
                setClass('table w-full max-w-full bordered text-center'),
                h::thead
                (
                    h::tr
                    (
                        h::th($lang->system->backup->type),
                        h::th($lang->system->backup->backupName),
                        h::th($lang->system->backup->size),
                        h::th($lang->system->backup->status)
                    )
                ),
                h::tbody
                (
                    $backupList
                )
            )
        )
    )
);
