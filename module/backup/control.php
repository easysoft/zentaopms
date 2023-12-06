<?php
declare(strict_types=1);
/**
 * The control file of backup of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     backup
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class backup extends control
{
    protected $backupPath;

    /**
     * __construct
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $this->backupPath = $this->backup->getBackupPath();

        if($this->app->methodName != 'setting')
        {
            if(!is_dir($this->backupPath))
            {
                if(!mkdir($this->backupPath, 0777, true)) $this->view->error = sprintf($this->lang->backup->error->noWritable, dirname($this->backupPath));
            }
            else
            {
                if(!is_writable($this->backupPath)) $this->view->error = sprintf($this->lang->backup->error->noWritable, $this->backupPath);
            }
            if(!is_writable($this->app->getTmpRoot())) $this->view->error = sprintf($this->lang->backup->error->noWritable, $this->app->getTmpRoot());
        }
    }

    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->loadModel('action');

        $backups = array();
        if(empty($this->view->error)) $backups = $this->backupZen->getBackupList();

        $this->view->title   = $this->lang->backup->common;
        $this->view->backups = $backups;

        if(!is_writable($this->backupPath))        $this->view->backupError = sprintf($this->lang->backup->error->plainNoWritable, $this->backupPath);
        if(!is_writable($this->app->getTmpRoot())) $this->view->backupError = sprintf($this->lang->backup->error->plainNoWritable, $this->app->getTmpRoot());
        $this->display();
    }

    /**
     * Ajax get disk space.
     *
     * @access public
     * @return void
     */
    public function ajaxGetDiskSpace()
    {
        set_time_limit(0);
        session_write_close();
        $diskSapce = $this->backup->getDiskSpace($this->backupPath);
        $diskSapce = explode(',', $diskSapce);

        $space = new stdclass();
        $space->freeSpace = intval($diskSapce[0]);
        $space->needSpace = intval($diskSapce[1]);

        echo json_encode($space);
    }

    /**
     * Backup.
     *
     * param   string $reload yes|no
     * @access public
     * @return void
     */
    public function backup(string $reload = 'no')
    {
        if($reload == 'yes') session_write_close();

        set_time_limit(0);

        $fileName = date('YmdHis') . mt_rand(0, 9);
        $result   = $this->backupZen->backupSQL($fileName, $reload);
        if($result['result'] == 'fail')
        {
            if($reload == 'yes') return print($result['message']);
            printf($result['message']);
        }

        $nofile = str_contains($this->config->backup->setting, 'nofile');
        if(!$nofile)
        {
            $result = $this->backupZen->backupFile($fileName, $reload);
            if($result['result'] == 'fail')
            {
                if($reload == 'yes') return print($result['message']);
                printf($result['message']);
            }

            $result = $this->backupZen->backupCode($fileName, $reload);
            if($result['result'] == 'fail')
            {
                if($reload == 'yes') return print($result['message']);
                printf($result['message']);
            }
        }


        /* Delete expired backup. */
        $this->backupZen->removeExpiredFiles();

        if($reload == 'yes') return print($this->lang->backup->success->backup);
        echo $this->lang->backup->success->backup . "\n";
    }

    /**
     * Restore.
     *
     * @param  string $fileName
     * @access public
     * @return void
     */
    public function restore(string $fileName)
    {
        set_time_limit(0);

        /* Restore database. */
        $result = $this->backupZen->restoreSQL($fileName);
        if($result['result'] == 'fail') return $this->send($result);

        /* Restore attachments. */
        $result = $this->backupZen->restoreFile($fileName);
        if($result['result'] == 'fail') return $this->send($result);

        return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "zui.Modal.alert('{$this->lang->backup->success->restore}').then(() => {loadCurrentPage()})"));
    }

    /**
     * remove PHP header.
     *
     * @param  string $fileName
     * @access public
     * @return void
     */
    public function rmPHPHeader(string $fileName)
    {
        if(file_exists($this->backupPath . $fileName . '.sql.php'))
        {
            $this->backup->removeFileHeader($this->backupPath . $fileName . '.sql.php');
            rename($this->backupPath . $fileName . '.sql.php', $this->backupPath . $fileName . '.sql');
        }
        if(file_exists($this->backupPath . $fileName . '.file.zip.php'))
        {
            $this->backup->removeFileHeader($this->backupPath . $fileName . '.file.zip.php');
            rename($this->backupPath . $fileName . '.file.zip.php', $this->backupPath . $fileName . '.file.zip');
        }
        if(file_exists($this->backupPath . $fileName . '.code.zip.php'))
        {
            $this->backup->removeFileHeader($this->backupPath . $fileName . '.code.zip.php');
            rename($this->backupPath . $fileName . '.code.zip.php', $this->backupPath . $fileName . '.code.zip');
        }

        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * Delete.
     *
     * @param  string $fileName
     * @access public
     * @return void
     */
    public function delete(string $fileName)
    {
        foreach(glob($this->backupPath . "{$fileName}*") as $backupFile)
        {
            if(is_dir($backupFile))
            {
                $zfile = $this->app->loadClass('zfile');
                $zfile->removeDir($backupFile);
                $this->backup->processSummary($backupFile, 0, 0, array(), 0, 'delete');
            }
            elseif(!unlink($backupFile))
            {
                return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->noDelete, $backupFile)));
            }
        }

        return $this->sendSuccess(array('load' => true));
    }

    /**
     * Change hold days.
     *
     * @access public
     * @return void
     */
    public function change()
    {
        if($_POST)
        {
            $data = fixer::input('post')->get();
            $this->loadModel('setting')->setItem('system.backup.holdDays', $data->holdDays);
            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->display();
    }

    /**
     * Setting backup
     *
     * @access public
     * @return void
     */
    public function setting()
    {
        /* Check safe file. */
        $statusFile = $this->loadModel('common')->checkSafeFile();
        if($statusFile)
        {
            $this->app->loadLang('extension');

            $search = $this->app->getBasePath();
            $pos    = strpos($statusFile, $search);
            $okFile = $statusFile;
            if($pos !== false) $okFile = substr_replace($statusFile, '', $pos, strlen($search));

            $this->view->error = sprintf($this->lang->extension->noticeOkFile, $okFile, $statusFile);
            return print($this->display());
        }

        if(strtolower($this->server->request_method) == "post")
        {
            $data = fixer::input('post')->join('setting', ',')->get();

            /*save change*/
            if(isset($data->holdDays)) $this->loadModel('setting')->setItem('system.backup.holdDays', $data->holdDays);

            $setting = '';
            if(isset($data->setting)) $setting = $data->setting;
            $this->loadModel('setting')->setItem('system.backup.setting', $setting);

            $settingDir = $data->settingDir;
            if($settingDir)
            {
                $settingDir = rtrim($settingDir, DS) . DS;
                if(!is_dir($settingDir) and mkdir($settingDir, 0777, true)) return $this->send(array('result' => 'fail', 'message' => $this->lang->backup->error->noCreateDir));
                if(!is_writable($settingDir)) return $this->send(array('result' => 'fail', 'message' => strip_tags(sprintf($this->lang->backup->error->noWritable, $settingDir))));
                if($data->settingDir == $this->app->getTmpRoot() . 'backup' . DS) $settingDir = '';
            }

            $this->setting->setItem('system.backup.settingDir', $settingDir);

            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }
        $this->display();
    }

    /**
     * Ajax get progress.
     *
     * @access public
     * @return void
     */
    public function ajaxGetProgress()
    {
        session_write_close();

        $files = glob($this->backupPath . '/*.*');
        rsort($files);

        $fileName = basename($files[0]);
        $fileName = substr($fileName, 0, strpos($fileName, '.'));

        $sqlFileName = $this->backupPath . $fileName . '.sql';
        if(!file_exists($sqlFileName)) $sqlFileName .= '.php';
        $sqlFileName = $this->backup->getBackupFile($fileName, 'sql');
        if($sqlFileName)
        {
            $summary = $this->backup->getBackupSummary($sqlFileName);
            $message = sprintf($this->lang->backup->progressSQL, $this->backup->processFileSize($summary['size']));
        }

        $attachFileName = $this->backup->getBackupFile($fileName, 'file');
        if($attachFileName)
        {
            $log = $this->backup->getBackupDirProgress($attachFileName);
            $message = sprintf($this->lang->backup->progressAttach, zget($log, 'allCount', 0), zget($log, 'count', 0));
        }

        $codeFileName = $this->backup->getBackupFile($fileName, 'code');
        if($codeFileName)
        {
            $log = $this->backup->getBackupDirProgress($codeFileName);
            $message = sprintf($this->lang->backup->progressCode, zget($log, 'allCount', 0), zget($log, 'count', 0));
        }

        return print($message);
    }
}
