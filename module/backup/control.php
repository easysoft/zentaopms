<?php
/**
 * The control file of backup of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2014 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     backup
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class backup extends control
{
    public function __construct()
    {
        parent::__construct();

        $this->backupPath = $this->app->getCacheRoot() . 'backup/';
        if(!is_dir($this->backupPath))
        {
            if(!mkdir($this->backupPath, 0777, true)) $this->view->error = sprintf($this->lang->backup->error->noWritable, dirname($this->backupPath));
        }
        else
        {
            if(!is_writable($this->backupPath)) $this->view->error = sprintf($this->lang->backup->error->noWritable, $this->backupPath);
        }
    }

    public function index()
    {
        $backups = array();
        if(empty($this->view->error))
        {
            foreach(glob("{$this->backupPath}*.sql.php") as $file)
            {
                $backupFile = new stdclass();
                $backupFile->time  = filemtime($file);
                $backupFile->name  = str_replace('.sql.php', '', basename($file));
                $backupFile->files[$file] = filesize($file);
                if(file_exists($this->backupPath . $backupFile->name . '.file.zip'))
                {
                    $backupFile->files[$this->backupPath . $backupFile->name . '.file.zip'] = filesize($this->backupPath . $backupFile->name . '.file.zip');
                }

                $backups[$backupFile->name] = $backupFile;
            }
        }
        krsort($backups);

        $this->view->title      = $this->lang->backup->common;
        $this->view->position[] = $this->lang->backup->common;
        $this->view->backups    = $backups;
        $this->display();
    }

    public function backup()
    {
        set_time_limit(0);
        $fileName = date('YmdHis') . mt_rand(0, 9);
        $result = $this->backup->backSQL($this->backupPath . $fileName . '.sql.php');
        if(!$result->result)
        {
            echo js::alert(sprintf($this->lang->backup->error->noWritable, $this->backupPath));
            die(js::reload('parent'));
        }

        $fp  = fopen($this->backupPath . $fileName . '.sql.php', 'r');
        $tmp = fopen($this->backupPath . $fileName . '.sql.php.tmp', 'w');
        fwrite($tmp, "<?php die();?>\n");
        while(($buffer = fgets($fp)) !== false) fwrite($tmp, $buffer);
        fclose($tmp);
        fclose($fp);
        rename($this->backupPath . $fileName . '.sql.php.tmp', $this->backupPath . $fileName . '.sql.php');

        if(extension_loaded('zlib'))
        {
            $result = $this->backup->backFile($this->backupPath . $fileName . '.file.zip');
            if(!$result->result)
            {
                echo js::alert(sprintf($this->lang->backup->error->backupFile, $result->error));
                die(js::reload('parent'));
            }
        }

        echo js::alert($this->lang->backup->success->backup);
        die(js::reload('parent'));
    }

    public function restore($fileName, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->backup->confirmRestore, inlink('restore', "fileName=$fileName&confirm=yes"), inlink('index'), 'self', 'parent'));
        }

        set_time_limit(0);
        $fp   = fopen($this->backupPath . $fileName . '.sql.php', 'r');
        $tmp  = fopen($this->backupPath . $fileName . '.sql', 'w');
        $line = 0;
        while(($buffer = fgets($fp)) !== false)
        {
            $line++;
            if($line == 1) continue;
            fwrite($tmp, $buffer);
        }
        fclose($tmp);
        fclose($fp);
        $result = $this->backup->restoreSQL($this->backupPath . $fileName . '.sql');
        unlink($this->backupPath . $fileName . '.sql');
        if(!$result->result)
        {
            echo js::alert(sprintf($this->lang->backup->error->restoreSQL, $result->error));
            die(js::reload('parent'));
        }

        if(file_exists($this->backupPath . $fileName . '.file.zip'))
        {
            $result = $this->backup->restoreFile($this->backupPath . $fileName . '.file.zip');
            if(!$result->result)
            {
                echo js::alert(sprintf($this->lang->backup->error->restoreFile, $result->error));
                die(js::reload('parent'));
            }
        }
        echo js::alert($this->lang->backup->success->restore);
        die(js::reload('parent'));
    }

    public function delete($fileName, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->backup->confirmDelete, inlink('delete', "fileName=$fileName&confirm=yes")));
        }
        if(file_exists($this->backupPath . $fileName . '.sql.php') and !unlink($this->backupPath . $fileName . '.sql.php'))
        {
            die(js::alert(sprintf($this->lang->backup->error->noDelete, $this->backupPath . $fileName . '.sql.php')));
        }
        if(file_exists($this->backupPath . $fileName . '.file.zip') and !unlink($this->backupPath . $fileName . '.file.zip'))
        {
            die(js::alert(sprintf($this->lang->backup->error->noDelete, $this->backupPath . $fileName . '.file.zip')));
        }

        die(js::reload('parent'));
    }
}
