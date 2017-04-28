<?php
/**
 * The control file of backup of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     backup
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class backup extends control
{
    /**
     * __construct 
     * 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $this->backupPath = $this->app->getTmpRoot() . 'backup/';
        if(!is_dir($this->backupPath))
        {
            if(!mkdir($this->backupPath, 0777, true)) $this->view->error = sprintf($this->lang->backup->error->noWritable, dirname($this->backupPath));
        }
        else
        {
            if(!is_writable($this->backupPath)) $this->view->error = sprintf($this->lang->backup->error->noWritable, $this->backupPath);
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
        $backups = array();
        if(empty($this->view->error))
        {
            $sqlFiles = glob("{$this->backupPath}*.sql.php");
            if(!empty($sqlFiles))
            {
                foreach($sqlFiles as $file)
                {
                    $backupFile = new stdclass();
                    $backupFile->time  = filemtime($file);
                    $backupFile->name  = str_replace('.sql.php', '', basename($file));
                    $backupFile->files[$file] = abs(filesize($file));
                    if(file_exists($this->backupPath . $backupFile->name . '.file.zip.php'))
                    {
                        $backupFile->files[$this->backupPath . $backupFile->name . '.file.zip.php'] = abs(filesize($this->backupPath . $backupFile->name . '.file.zip.php'));
                    }
                    if(file_exists($this->backupPath . $backupFile->name . '.code.zip.php'))
                    {
                        $backupFile->files[$this->backupPath . $backupFile->name . '.code.zip.php'] = abs(filesize($this->backupPath . $backupFile->name . '.code.zip.php'));
                    }

                    $backups[$backupFile->name] = $backupFile;
                }
            }
        }
        krsort($backups);

        $this->view->title      = $this->lang->backup->common;
        $this->view->position[] = $this->lang->backup->common;
        $this->view->backups    = $backups;
        $this->display();
    }

    /**
     * Backup 
     * 
     * @access public
     * @return void
     */
    public function backup($reload = 'no')
    {
        set_time_limit(7200);
        $fileName = date('YmdHis') . mt_rand(0, 9);
        $result = $this->backup->backSQL($this->backupPath . $fileName . '.sql.php');
        if(!$result->result)
        {
            if($reload == 'yes')
            {
                echo js::alert(sprintf($this->lang->backup->error->noWritable, $this->backupPath));
                die(js::reload('parent'));
            }
            else
            {
                printf($this->lang->backup->error->noWritable, $this->backupPath);
            }
        }
        $this->backup->addFileHeader($this->backupPath . $fileName . '.sql.php');

        if(extension_loaded('zlib'))
        {
            $result = $this->backup->backFile($this->backupPath . $fileName . '.file.zip.php');
            if(!$result->result)
            {
                if($reload == 'yes')
                {
                    echo js::alert(sprintf($this->lang->backup->error->backupFile, $result->error));
                    die(js::reload('parent'));
                }
                else
                {
                    printf($this->lang->backup->error->backupFile, $result->error);
                }
            }
            $this->backup->addFileHeader($this->backupPath . $fileName . '.file.zip.php');

            $result = $this->backup->backCode($this->backupPath . $fileName . '.code.zip.php');
            if(!$result->result)
            {
                if($reload == 'yes')
                {
                    echo js::alert(sprintf($this->lang->backup->error->backupCode, $result->error));
                    die(js::reload('parent'));
                }
                else
                {
                    printf($this->lang->backup->error->backupCode, $result->error);
                }
            }
            $this->backup->addFileHeader($this->backupPath . $fileName . '.code.zip.php');
        }

        /* Delete expired backup. */
        $backupFiles = glob("{$this->backupPath}*.php");
        if(!empty($backupFiles))
        {
            $time = time();
            foreach($backupFiles as $file)
            {
                if($time - filemtime($file) > $this->config->backup->holdDays * 24 * 3600) unlink($file);
            }
        }

        if($reload == 'yes')
        {
            echo js::alert($this->lang->backup->success->backup);
            die(js::reload('parent'));
        }
        else
        {
            echo $this->lang->backup->success->backup . "\n";
        }
    }

    /**
     * Restore 
     * 
     * @param  string $fileName 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function restore($fileName, $confirm = 'no')
    {
        if($confirm == 'no') $this->send(array('result' => 'fail', 'message' => $this->lang->backup->confirmRestore));

        set_time_limit(7200);

        /* Restore database. */
        $this->backup->removeFileHeader($this->backupPath . $fileName . '.sql.php');
        $result = $this->backup->restoreSQL($this->backupPath . $fileName . '.sql.php');
        $this->backup->addFileHeader($this->backupPath . $fileName . '.sql.php');
        if(!$result->result) $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->restoreSQL, $result->error)));

        /* Restore attatchments. */
        if(file_exists($this->backupPath . $fileName . '.file.zip.php'))
        {
            $this->backup->removeFileHeader($this->backupPath . $fileName . '.file.zip.php');
            $result = $this->backup->restoreFile($this->backupPath . $fileName . '.file.zip.php');
            $this->backup->addFileHeader($this->backupPath . $fileName . '.file.zip.php');
            if(!$result->result) $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->resotreFile, $result->error)));
        }

        $this->send(array('result' => 'success', 'message' => $this->lang->backup->success->restore));
    }

    /**
     * Delete 
     * 
     * @param  string $fileName 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function delete($fileName, $confirm = 'no')
    {
        if($confirm == 'no') die(js::confirm($this->lang->backup->confirmDelete, inlink('delete', "fileName=$fileName&confirm=yes")));

        /* Delete database file. */
        if(file_exists($this->backupPath . $fileName . '.sql.php') and !unlink($this->backupPath . $fileName . '.sql.php'))
        {
            die(js::alert(sprintf($this->lang->backup->error->noDelete, $this->backupPath . $fileName . '.sql.php')));
        }

        /* Delete attatchments file. */
        if(file_exists($this->backupPath . $fileName . '.file.zip.php') and !unlink($this->backupPath . $fileName . '.file.zip.php'))
        {
            die(js::alert(sprintf($this->lang->backup->error->noDelete, $this->backupPath . $fileName . '.file.zip.php')));
        }

        /* Delete code file. */
        if(file_exists($this->backupPath . $fileName . '.code.zip.php') and !unlink($this->backupPath . $fileName . '.code.zip.php'))
        {
            die(js::alert(sprintf($this->lang->backup->error->noDelete, $this->backupPath . $fileName . '.code.zip.php')));
        }

        die(js::reload('parent'));
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
            die(js::reload('parent.parent'));
        }

        $this->display();
    }
}
