<?php
/**
 * The model file of backup module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     backup
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class backupModel extends model
{
    /**
     * Backup SQL 
     * 
     * @param  string    $backupFile 
     * @access public
     * @return object
     */
    public function backSQL($backupFile)
    {
        $zdb = $this->app->loadClass('zdb');
        return $zdb->dump($backupFile);
    }

    /**
     * Backup file.
     * 
     * @param  string    $backupFile 
     * @access public
     * @return object
     */
    public function backFile($backupFile)
    {
        $return = new stdclass();
        $return->result = true;
        $return->error  = '';

        $this->app->loadClass('pclzip', true);
        $zip = new pclzip($backupFile);
        $zip->create($this->app->getAppRoot() . 'www/data/', PCLZIP_OPT_REMOVE_PATH, $this->app->getAppRoot() . 'www/data/');
        if($zip->errorCode() != 0)
        {
            $return->result = false;
            $return->error  = $zip->errorInfo();
        }

        return $return;
    }

    /**
     * Backup code.
     * 
     * @param  string    $backupFile 
     * @access public
     * @return object
     */
    public function backCode($backupFile)
    {
        $return = new stdclass();
        $return->result = true;
        $return->error  = '';

        $appRoot     = $this->app->getAppRoot();
        $fileList    = glob($appRoot . '*');
        $wwwFileList = glob($appRoot . 'www/*');

        $tmpFile  = array_search($appRoot . 'tmp', $fileList);
        $wwwFile  = array_search($appRoot . 'www', $fileList);
        $dataFile = array_search($appRoot . 'www/data', $wwwFileList);
        unset($fileList[$tmpFile]);
        unset($fileList[$wwwFile]);
        unset($wwwFileList[$dataFile]);

        $fileList = array_merge($fileList, $wwwFileList);

        $this->app->loadClass('pclzip', true);
        $zip = new pclzip($backupFile);
        $zip->create($fileList, PCLZIP_OPT_REMOVE_PATH, $appRoot);
        if($zip->errorCode() != 0)
        {
            $return->result = false;
            $return->error  = $zip->errorInfo();
        }

        return $return;
    }

    /**
     * Restore SQL 
     * 
     * @param  string    $backupFile 
     * @access public
     * @return object
     */
    public function restoreSQL($backupFile)
    {
        $zdb = $this->app->loadClass('zdb');
        return $zdb->import($backupFile);
    }

    /**
     * Restore File 
     * 
     * @param  string    $backupFile 
     * @access public
     * @return object
     */
    public function restoreFile($backupFile)
    {
        $return = new stdclass();
        $return->result = true;
        $return->error  = '';

        $this->app->loadClass('pclzip', true);
        $zip = new pclzip($backupFile);
        if($zip->extract(PCLZIP_OPT_PATH, $this->app->getAppRoot() . 'www/data/') == 0)
        {
            $return->result = false;
            $return->error  = $zip->errorInfo();
        }

        return $return;
    }

    /**
     * Add file header.
     * 
     * @param  string    $fileName 
     * @access public
     * @return bool
     */
    public function addFileHeader($fileName)
    {
        $firstline = false;
        $die       = "<?php die();?>\n";
        $fileSize  = filesize($fileName);

        $fh    = fopen($fileName, 'c+');
        $delta = strlen($die);
        while(true)
        {
            $offset = ftell($fh);
            $line   = fread($fh, 1024 * 1024);
            if(!$firstline)
            {
                $line = $die . $line;
                $firstline = true;
            }
            else
            {
                $line = $compensate . $line;
            }
            
            $compensate = fread($fh, $delta);
            fseek($fh, $offset);
            fwrite($fh, $line);

            if(ftell($fh) >= $fileSize)
            {
                fwrite($fh, $compensate);
                break;
            }
        }
        fclose($fh);
        return true;
    }

    /**
     * Remove file header.
     * 
     * @param  string    $fileName 
     * @access public
     * @return bool
     */
    public function removeFileHeader($fileName)
    {
        $firstline = false;
        $die       = "<?php die();?>\n";
        $fileSize  = filesize($fileName);

        $fh = fopen($fileName, 'c+');
        while(true)
        {
            $offset = ftell($fh);
            if($firstline and $delta) fseek($fh, $offset + $delta);
            $line = fread($fh, 1024 * 1024);
            if(!$firstline)
            {
                $firstline    = true;
                $beforeLength = strlen($line);
                $line         = str_replace($die, '', $line);
                $afterLength  = strlen($line);
                $delta        = $beforeLength - $afterLength;
                if($delta == 0)
                {
                    fclose($fh);
                    return true;
                }
            }
            fseek($fh, $offset);
            fwrite($fh, $line);

            if(ftell($fh) >= $fileSize - $delta) break;
        }
        ftruncate($fh, ($fileSize - $delta));
        fclose($fh);
        return true;
    }
}
