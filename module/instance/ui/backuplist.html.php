<?php
declare(strict_types=1);
/**
 * The maintain view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zhai Xiaojian<zhaixiaojian@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

$backups = initTableData($backupList, $config->backup->dtable->fieldList, $this->instance);
$hasRestoreLog = false;
foreach($backupList as &$backup)
{
    /* Initialization calculation and assignment. */
    $status        = $backup->status;
    $restoreStatus = '';
    $backupSize = isset($backup->backup_details->db[0]) ? intval(zget($backup->backup_details->db[0], 'size', 0)) : 0;
    $backupSize += isset($backup->backup_details->volume[0]) ? intval(zget($backup->backup_details->volume[0], 'doneBytes', 0)) : 0;

    $backup->instanceId = $instance->id;
    $backup->operator   = zget($lang->instance->backup->operators, $backup->username, $backup->username);
    $backup->status     = zget($lang->instance->backup->statusList, strtolower($backup->status));
    $backup->backupSize = helper::formatKB($backupSize);

    /* Mark recently restored information. */
    $backup->latestRestoreTime       =  0;
    $backup->latestRestoreStatus = '';
    foreach($backup->restores as $restore)
    {
        $hasRestoreLog = true;
        if($restore->create_time > $backup->latestRestoreTime)
        {
            $backup->latestRestoreTime   = $restore->create_time;
            $restoreStatus               = $restore->status;
            $backup->latestRestoreStatus = zget($lang->instance->restore->statusList, $restore->status, $restore->status);
        }
    }
    $backup->latestRestoreTime = $backup->latestRestoreTime == 0 ? '' : date('Y-m-d H:i:s', $backup->latestRestoreTime);
    $backup->restoreTime = zget($lang->instance->backup->operators, $backup->username, $backup->username);

    if(in_array($status, array('processing', 'inprogress')) || in_array($restoreStatus, array('pending', 'inprogress')))  $backup->actions[0]['disabled'] = true;
}
dtable
(
    set::cols($config->backup->dtable->fieldList),
    set::data($backups),
    set::sortLink(createLink('instance', 'backuplist', "id={$instance->id}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&pageID={$pager->pageID}")),
    set::footPager(usePager())
);
