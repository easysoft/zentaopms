<?php
/**
 * The control file of common module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chen congzhi <congzhi@cnezsoft.com>
 * @package     common
 * @version     $Id$
 * @link        http://www.zentao.net
 */
include '../config/config.php';

$modules    = array();
$moduleRoot = realpath('../module/') . '/';

if(is_dir($moduleRoot))
{
    if($dh = opendir($moduleRoot))
    {
        while($module = readdir($dh))
        {
            if(strpos(basename($module), '.') === false) $modules[] = $module;
        }
        closedir($dh);
    }
}
else
{
    die("The module you input does not exist. \n");
}

foreach($modules as $module)
{ 
    /*  设定各个目录。*/
    $extRoot      = $moduleRoot . DIRECTORY_SEPARATOR. $module . DIRECTORY_SEPARATOR . 'ext';
    $extControl   = $extRoot . DIRECTORY_SEPARATOR . 'control';
    $extModel     = $extRoot . DIRECTORY_SEPARATOR . 'model';
    $extView      = $extRoot . DIRECTORY_SEPARATOR . 'view';
    $extConfig    = $extRoot . DIRECTORY_SEPARATOR . 'config';
    $extLang      = $extRoot . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR;

    /* 建立各个扩展目录 */
    if(!file_exists($extRoot))    mkdir($extRoot,    0777);
    if(!file_exists($extControl)) mkdir($extControl, 0777);      
    if(!file_exists($extModel))   mkdir($extModel,   0777);
    if(!file_exists($extView))    mkdir($extView,    0777);
    if(!file_exists($extConfig))  mkdir($extConfig,  0777);
    if(!file_exists($extLang))    mkdir($extLang,    0777);

    /* 创建语言目录。*/
    $langs = array_keys($config->langs);
    foreach($langs as $lang)
    {
        $langPath = $extLang . $lang;
        if(!file_exists($langPath)) mkdir($langPath, 0777);
    }

    echo "init $module ... \n";
}
