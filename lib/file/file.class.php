<?php
class file
{
    /**
     * Copy a directory from an directory to another directory.
     * 
     * @param  string    $from 
     * @param  string    $to 
     * @access public
     * @return array     copied files.
     */
    public function copyDir($from, $to)
    {
        static $copiedFiles = array();

        if(!is_dir($from) or !is_readable($from)) return $copiedFiles;
        if(!is_dir($to))
        {
            if(!is_writable(dirname($to))) return $copiedFiles;
            mkdir($to);
        }

        $from    = realpath($from) . '/';
        $to      = realpath($to) . '/';
        $entries = scandir($from);

        foreach($entries as $entry)
        {
            if($entry == '.' or $entry == '..' or $entry == '.svn') continue;

            $fullEntry = $from . $entry;
            if(is_file($fullEntry))
            {
                if(file_exists($to . $entry))
                {
                    unlink($to . $entry);
                }
                copy($fullEntry, $to . $entry);
                $copiedFiles[] = $to . $entry;
            }
            else
            {
                $nextFrom = $from . $entry;
                $nextTo   = $to . $entry;
                $this->copyDir($nextFrom, $nextTo);
            }
        }
        return $copiedFiles;
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
}
