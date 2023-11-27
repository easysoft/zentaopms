<?php
declare(strict_types=1);
/**
 * The zen file of backup module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     backup
 * @link        https://www.zentao.net
 */
class backupZen extends backup
{
    protected function getBackupList(): array
    {
        $backupPath = $this->backup->getBackupPath();
        $sqlFiles   = glob("{$backupPath}*.sql*");
        if(empty($sqlFiles)) return array();

        $backupList = array();
        foreach($sqlFiles as $file)
        {
            $fileName = basename($file);
            $backupFile = new stdclass();
            $backupFile->time = filemtime($file);
            $backupFile->name = substr($fileName, 0, strpos($fileName, '.'));
            $backupFile->files[$file] = $this->backup->getBackupSummary($file);

            $fileBackup = $this->backup->getBackupFile($backupFile->name, 'file');
            if($fileBackup) $backupFile->files[$fileBackup] = $this->backup->getBackupSummary($fileBackup);

            $codeBackup = $this->backup->getBackupFile($backupFile->name, 'code');
            if($codeBackup) $backupFile->files[$codeBackup] = $this->backup->getBackupSummary($codeBackup);

            $backupList[$backupFile->name] = $backupFile;
        }
        krsort($backupList);

        return $backupList;
    }
}

