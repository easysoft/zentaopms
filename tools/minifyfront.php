<?php
/**
* This file is used to compress css and js files.
*/

$baseDir = dirname(dirname(__FILE__));

//--------------------------------- PROCESS JS FILES ------------------------------ //

/* Set jsRoot and jqueryRoot. */
$jsRoot     = $baseDir . '/www/js/';
$jqueryRoot = $jsRoot . 'jquery/';

/* Set js files to combined. */
$jsFiles[] = $jqueryRoot . 'lib.js'; 
$jsFiles[] = $jqueryRoot . 'tablesorter/min.js';
$jsFiles[] = $jqueryRoot . 'tablesorter/metadata.js';
$jsFiles[] = $jsRoot     . 'zui/min.js'; 
$jsFiles[] = $jsRoot     . 'my.full.js';

/* Combine these js files. */
$allJSFile  = $jsRoot . 'all.js';
$jsCode = '';
foreach($jsFiles as $jsFile) $jsCode .= "\n". file_get_contents($jsFile);
file_put_contents($allJSFile, $jsCode);

/* Compress it. */
`java -jar ~/bin/yuicompressor/build/yuicompressor.jar --type js $allJSFile -o $allJSFile`;

//-------------------------------- PROCESS CSS FILES ------------------------------ //

/* Define the themeRoot. */
$themeRoot  = $baseDir . '/www/theme/';

/* Iinclude config and lang file to get langs and themes. */
include $baseDir . '/config/config.php';
$lang = new stdclass();
$lang->productCommon = '';
$lang->projectCommon = '';
include $baseDir . '/module/common/lang/zh-cn.php';
$langs  = array_keys($config->langs);
$themes = array_keys($lang->themes);

/* Create css files for every them and every lang. */
$zuiCode  = str_replace('../fonts', '../zui/fonts', file_get_contents($themeRoot . 'zui/css/min.css'));
foreach($langs as $lang)
{
    foreach($themes as $theme)
    {
        /* Common css files. */
        $cssCode  = $zuiCode;
        $cssCode .= file_get_contents($themeRoot  . 'default/style.css');

        /* Css file for current lang and current them. */
        $cssCode .= file_get_contents($themeRoot . "lang/$lang.css");
        if($theme != 'default')
        {
            $themCode = file_get_contents($themeRoot . $theme . '/style.css');
            $cssCode .= str_replace('./images', "../$theme/images", $themCode);
        }

        /* Combine them. */
        $cssFile = $themeRoot . "default/$lang.$theme.css";
        file_put_contents($cssFile, $cssCode);

        /* Compress it. */
        `java -jar ~/bin/yuicompressor/build/yuicompressor.jar --type css $cssFile -o $cssFile`;
    }
}
