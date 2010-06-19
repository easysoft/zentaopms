<?php
/**
 * The control file of common module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chen congzhi <congzhi@cnezsoft.com>
 * @package     common
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */

$moduleRoot   = $argv[1];
if(!isset($argv[1]))
{
    die("Please input the directory path of 'module'!");
}
$i     = 0;
$fileName = array();

$ControlDir     = "control";
$ModelDir       = "model";
$ConfigDir      = "config";
$EnFile         = "en.php";
$Zh_chFile      = "zh-ch.php";

if(is_dir($moduleRoot))
{
    if($dh = opendir($moduleRoot))
    {
        while($file = readdir($dh))
        {
            $array[$i] = $file; 
            $i++;

        }
    }
}

for($j=3; $j<$i; $j++) // 各个模块从第三个开始依次排序
{ 
    $OptRoot        = $moduleRoot . "\\$fileName[$j]\\opt";     // windows linux 未判断
    $OptLang        = $OptRoot . "\\lang";
    $OptView        = $OptRoot . "\\view";
    $OptControl     = $OptRoot ."\\$ControlDir";
    $OptModel       = $OptRoot."\\$ModelDir";
    $OptConfig      = $OptRoot."\\$ConfigDir"; 
    $OptLangEn      = $OptLang."\\$EnFile";
    $OptLangZh_ch   = $OptLang."\\$Zh_chFile";

    /* 建立各个扩展目录 */
    if(!file_exists($OptRoot))      mkdir($OptRoot,0777);
    if(!file_exists($OptLang))      mkdir($OptLang,0777);
    if(!file_exists($OptView))      mkdir($OptView,0777);
    if(!file_exists($OptControl))   touch($OptControl);      
    if(!file_exists($OptModel))     touch($OptModel);
    if(!file_exists($OptConfig))    touch($OptConfig);
    if(!file_exists($OptLangEn))    touch($OptLangEn);
    if(!file_exists($OptLangZh_ch)) touch($OptLangZh_ch);
}

closedir($dh);
?>
