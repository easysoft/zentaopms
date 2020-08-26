<?php
/**
 * The zfile library of zentaopms.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     Zfile
 * @version     $Id: zfile.class.php 2605 2013-01-09 07:22:58Z wwccss $
 * @link        http://www.zentao.net
 */
class zfile
{
    /**
     * Copy a directory from an directory to another directory.
     * 
     * @param  string    $from 
     * @param  string    $to 
     * @param  bool      $logLevel
     * @param  string    $logFile 
     * @access public
     * @return array     copied files, count, size or message.
     */
    public function copyDir($from, $to, $logLevel = false, $logFile = '')
    {
        static $copiedFiles = array();
        static $errorFiles  = array();
        $count = $size = 0;

        $log['copiedFiles'] = array();
        $log['count']       = 0;
        $log['size']        = 0;

        if(!is_dir($from) or !is_readable($from))
        {
            if(!is_dir($from))      $log['message'] = "$from: Dir is not exists";
            if(!is_readable($from)) $log['message'] = "$from: Permission denied";
        }
        if(!is_dir($to) and !@mkdir($to, 0777, true)) $log['message'] = "$to: Permission denied";
        if(is_dir($to)  and !is_writeable($to))       $log['message'] = "$to: Permission denied";
        if($logFile     and !file_exists($logFile))   touch($logFile);
        if(!empty($log['message'])) return $log;

        $from = realpath($from) . '/';
        $to   = realpath($to) . '/';

        $entries = scandir($from);
        foreach($entries as $entry)
        {
            if($entry == '.' or $entry == '..' or $entry == '.svn' or $entry == '.git') continue;

            $fullEntry = $from . $entry;
            if(is_file($fullEntry))
            {
                if(file_exists($to . $entry)) unlink($to . $entry);

                if(copy($fullEntry, $to . $entry))
                {
                    if($logLevel) $copiedFiles[] = $to . $entry;
                    $count += 1;
                    $size  += filesize($fullEntry);

                    if($logFile and file_exists($logFile))
                    {
                        $summary = json_decode(file_get_contents($logFile), true);

                        if(empty($summary)) $summary = array();
                        if(empty($summary['count'])) $summary['count'] = 0;
                        $summary['count'] += 1;

                        file_put_contents($logFile, json_encode($summary));
                    }
                }
                else
                {
                    $errorFiles[] = $fullEntry;
                }
            }
            else
            {
                $nextFrom = $fullEntry;
                $nextTo   = $to . $entry;
                $result   = $this->copyDir($nextFrom, $nextTo, $logLevel, $logFile);
                $count   += $result['count'];
                $size    += $result['size'];
            }
        }

        if($logLevel) $log['copiedFiles'] = $copiedFiles;
        $log['errorFiles'] = $errorFiles;
        $log['count']      = $count;
        $log['size']       = $size;
        $log['logFile']    = $logFile;

        return $log;
    }

    /**
     * Get count.
     * 
     * @param  string $dir 
     * @access public
     * @return int
     */
    public function getCount($dir)
    {
        if(!file_exists($dir)) return 0;
        if(is_file($dir)) return 1;

        $count   = 0;
        $entries = scandir($dir);
        foreach($entries as $entry)
        {
            if($entry == '.' or $entry == '..' or $entry == '.svn' or $entry == '.git') continue;

            $fullEntry = $dir . '/' . $entry;
            $count += $this->getCount($fullEntry);
        }

        return $count;
    }

    /**
     * Remove a dir.
     * 
     * @param  string    $dir 
     * @access public
     * @return bool
     */
    public function removeDir($dir)
    {
        if(empty($dir)) return true;
        $dir = realpath($dir) . '/';
        if($dir == '/') return false;

        if(!is_writable($dir)) return false;
        if(!is_dir($dir)) return true;

        $entries = scandir($dir);
        foreach($entries as $entry)
        {
            if($entry == '.' or $entry == '..' or $entry == '.svn') continue;

            $fullEntry = $dir . $entry;
            if(is_file($fullEntry))
            {
                unlink($fullEntry);
            }
            else
            {
                $this->removeDir($fullEntry);
            }
        }
        if(!@rmdir($dir)) return false;
        return true;
    }

    /**
     * Get files under a directory recursive.
     * 
     * @param  string    $dir 
     * @param  array     $exceptions 
     * @access private
     * @return array
     */
    public function readDir($dir, $exceptions = array())
    {
        static $files = array();

        if(!is_dir($dir)) return $files;

        $dir    = realpath($dir) . '/';
        $entries = scandir($dir);

        foreach($entries as $entry)
        {
            if($entry == '.' or $entry == '..' or $entry == '.svn') continue;
            if(in_array($entry, $exceptions)) continue;

            $fullEntry = $dir . $entry;
            if(is_file($fullEntry))
            {
                $files[] = $dir . $entry;
            }
            else
            {
                $nextDir = $dir . $entry;
                $this->readDir($nextDir);
            }
        }
        return $files;
    }

    /**
     * Make a dir.
     * 
     * @param  string    $dir 
     * @access public
     * @return bool
     */
    public function mkdir($dir)
    {
        return mkdir($dir, 0755, true);
    }

    /**
     * Remove a file
     * 
     * @param  string    $file 
     * @access public
     * @return bool
     */
    public function removeFile($file)
    {
        if(!file_exists($file)) return true;
        return @unlink($file);
    }

   /**
    * Batch remove files. use glob function.
    * 
    * @param  string    $patern
    * @access public
    * @return avoid
    */
    public function batchRemoveFile($patern)
    {
        $files = glob($patern);
        foreach($files as $file) @unlink($file);
    }

    /**
     * Remove a file
     * 
     * @param  string    from
     * @param  string    to
     * @access public
     * @return bool
     */
    public function copyFile($from, $to)
    {
        return @copy($from, $to);
    }

    /**
     * Rename a file or directory.
     * 
     * @param  string    from
     * @param  string    to
     * @access public
     * @return bool
     */
    public function rename($from, $to)
    {
        return rename($from, $to);
    }

    /**
     * Get file size.
     * 
     * @param  string    $file 
     * @access public
     * @return int
     */
    public function getFileSize($file)
    {
        return abs(filesize($file));
    }

    /**
     * Get directory size.
     * 
     * @param  string    $dir
     * @access public
     * @return int 
     */
    public function getDirSize($dir)
    {
        $size = 0;
        foreach(glob("$dir/*") as $file)
        {
            if(is_dir($file))
            {
                $size += $this->getDirSize($file);
            }
            else
            {
                $size += $this->getFileSize($file);
            }
        }
        return $size;
    }
}
