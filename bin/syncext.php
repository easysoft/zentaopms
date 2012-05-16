#!/usr/bin/env php
<?php
/**
 * This file is used to sync extension files to the target pms directory.
 * 
 * @author chunsheng wang chunsheng@cnezsoft.com
 * @version $Id$
 */

//sudo pecl install channel://pecl.php.net/inotify-0.1.5

//extension=inotify.so" to php.ini

/* Get params from argvs. */
if(!isset($argv[1])) die("php syncext.php from target sleep\n");
$from   = $argv[1];
$target = isset($argv[2]) ? $argv[2] : '/home/z/zentaopms';
$sleep  = isset($argv[3]) ? $argv[3] : 5;

/* sync files.  */
while(true)
{
    syncFiles(getFiles($from), $from, $target);
    sleep($sleep);
}

/**
 * Get files under a directory recursive.
 * 
 * @param  string    $dir 
 * @param  array     $exceptions 
 * @access private
 * @return array
 */
function getFiles($dir, $exceptions = array())
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
            getFiles($nextDir);
        }
    }
    return $files;
}

/**
 * Sync extension files to target.
 * 
 * @param  array    $files 
 * @param  string   $from 
 * @param  string   $target 
 * @access public
 * @return void
 */
function syncFiles($files, $from, $target)
{
    $from   = realpath($from);
    $target = realpath($target);
    static $copied = array();

    foreach($files as $file)
    {
        $relativePath = str_replace($from, '', $file); 
        $targetFile   = $target . $relativePath;
        $targetPath   = dirname($targetFile);

        /* If file not exists, remove the target. */
        if(!is_file($file))
        {
            @unlink($targetFile);
            continue;
        }

        if(!is_dir($targetPath)) mkdir($targetPath, 0755, true);
        $ctime = filectime($file);
        if(!isset($copied[$file]) or $copied[$file] != $ctime)
        {
            copy($file, $targetFile);
            $copied[$file] = $ctime;
            echo "$file copyed\n";
        }
    }
}
