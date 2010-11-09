#!/usr/bin/env php
<?php
/**
 * 测试import方法
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      chunsheng.wang <chunsheng@cnezsoft.com>
 * @package     Testing
 * @version     $Id: case004.php 133 2010-09-11 07:22:48Z wwccss $
 * @link        http://www.zentao.net
 * @license     http://opensource.org/licenses/lgpl-3.0.html LGPL
 */
include '../../helper.class.php';

/* 首次包含。*/
helper::import('import1.php');
printIncluded();

/* 重复包含。*/
helper::import('import1.php');
printIncluded();

/* 包含第二个文件。*/
helper::import('import2.php');
printIncluded();

/* 包含不存在的文件。*/
var_dump(helper::import('noexits.php'));

/**
 * 只打印包含文件的文件名。
 * 
 * @access public
 * @return void
 */
function printIncluded()
{
    $files = get_included_files();
    foreach($files as $file)
    {
        echo basename($file) . "\n";
    }
    echo "\n";
}
?>
