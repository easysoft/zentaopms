<?php
/**
 * The model file of extension module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class extensionModel extends model
{
    /**
     * Copy a directory from an directory to another directory.
     * 
     * @param  string    $from 
     * @param  string    $to 
     * @access public
     * @return array     copied files.
     */
    public function xcopy($from, $to)
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
            if($entry == '.' or $entry == '..') continue;

            $fullEntry = $from . $entry;
            if(is_file($fullEntry))
            {
                copy($fullEntry, $to . $entry);
                $copiedFiles[] = $fullEntry;
            }
            else
            {
                $nextFrom = $from . $entry;
                $nextTo   = $to . $entry;
                $this->xcopy($nextFrom, $nextTo);
            }
        }
        return $copiedFiles;
    }
}
