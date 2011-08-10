<?php
/**
 * This file is used to sync extension files to the target pms directory.
 * 
 * @author chunsheng wang chunsheng@cnezsoft.com
 * @version $Id$
 */

/* Get params from argvs. */
if(!isset($argv[2])) die("php syncext.php from target sleep\n");
$from   = $argv[1];
$target = $argv[2];
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
    foreach($files as $file)
    {
        $relativePath = str_replace($from, '', $file); 
        $targetFile   = $target . $relativePath;
        $targetPath   = dirname($targetFile);
        if(!is_dir($targetPath)) mkdir($targetPath, 0755, true);
        copy($file, $targetFile);
    }
}
