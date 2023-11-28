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
    /**
     * 获取备份文件列表
     * Get backup files list.
     *
     * @access protected
     * @return array
     */
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

    /**
     * 备份SQL文件
     * backupSQL
     *
     * @param  string    $fileName
     * @access protected
     * @return array
     */
    protected function backupSQL(string $fileName): array
    {
        $backFileName = "{$this->backupPath}{$fileName}.sql";
        if(str_contains($this->config->backup->setting, 'nosafe')) $backFileName .= '.php';

        $result = $this->backup->backSQL($backFileName);
        if(!$result->result) return array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->noWritable, $this->backupPath));

        if(!$nosafe) $this->backup->addFileHeader($backFileName);
        return array('result' => 'success');
    }

    /**
     * 备份附件
     * Backup appendix file.
     *
     * @param  string    $fileName
     * @access protected
     * @return array
     */
    protected function backupFile(string $fileName): array
    {
        if(str_contains($this->config->backup->setting, 'nofile')) array('result' => 'success');

        $result = $this->backup->backFile("{$this->backupPath}{$fileName}.file");
        if(!$result->result) return array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->backupFile, $result->error));
        return array('result' => 'success');
    }

    /**
     * 备份代码
     * Backup code
     *
     * @param  string    $fileName
     * @access protected
     * @return array
     */
    protected function backupCode(string $fileName): array
    {
        if(str_contains($this->config->backup->setting, 'nofile')) array('result' => 'success');

        $result = $this->backup->backCode("{$this->backupPath}{$fileName}.code");
        if(!$result->result) return array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->backupCode, $result->error));
        return array('result' => 'success');
    }

    /**
     * 删除过期文件。
     * Remove expired backup files.
     *
     * @access protected
     * @return void
     */
    protected function removeExpiredFiles()
    {
        $backupFiles = glob("{$this->backupPath}*.*");
        if(empty($backupFiles)) return;

        $time  = time();
        $zfile = $this->app->loadClass('zfile');
        foreach($backupFiles as $file)
        {
            /* Only delete backup file. */
            $fileName = basename($file);
            if(!preg_match('/[0-9]+\.(sql|file|code)/', $fileName)) continue;

            /* Remove before holdDays file. */
            if($time - filemtime($file) > $this->config->backup->holdDays * 24 * 3600)
            {
                $rmFunc = is_file($file) ? 'removeFile' : 'removeDir';
                $zfile->{$rmFunc}($file);
                if($rmFunc == 'removeDir') $this->backup->processSummary($file, 0, 0, array(), 0, 'delete');
            }
        }
    }

    /**
     * 还原SQL
     * Restore SQL
     *
     * @param  string    $fileName
     * @access protected
     * @return array
     */
    protected function restoreSQL(string $fileName): array
    {
        $backupFile = $this->backup->getBackupFile($fileName, 'sql');
        if(empty($backupFile)) return array('result' => 'success');

        $extension = substr($backupFile, -3);
        if($extension == 'php') $this->backup->removeFileHeader($backupFile);

        $result = $this->backup->restoreSQL($backupFile);

        if($extension == 'php') $this->backup->addFileHeader($backupFile);

        if(!$result->result) return array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->restoreSQL, $result->error));
        return array('result' => 'success');
    }

    /**
     * 还原附件
     * Restore File.
     *
     * @param  string    $fileName
     * @access protected
     * @return array
     */
    protected function restoreFile(string $fileName): array
    {
        $backupFile = $this->backup->getBackupFile($fileName, 'file');
        if(empty($backupFile)) return array('result' => 'success');

        $extension = substr($backupFile, -3);
        if($extension == 'php') $this->backup->removeFileHeader($backupFile);

        $result = $this->backup->restoreFile($fileBackup);

        if($extension == 'php') $this->backup->addFileHeader($backupFile);

        if(!$result->result) return array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->restoreFile, $result->error));
        return array('result' => 'success');
    }
}
