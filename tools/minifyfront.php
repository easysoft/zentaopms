<?php
/**
* This file is used to compress css and js files.
*/

$baseDir = dirname(dirname(__FILE__));
include $baseDir . '/framework/helper.class.php';

//$miniCSSTool = getenv('MINIFY_CSS_PATH');
//$miniJSTool  = getenv('MINIFY_JS_PATH');
$miniCSSTool = '/home/z/ci/minify/minifyCSS.php';
$miniJSTool  = '/home/z/ci/minify/minifyJS.php';

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
foreach($jsFiles as $jsFile) $jsCode .= "\n". str_replace('/*!', '/*', file_get_contents($jsFile));
file_put_contents($allJSFile, $jsCode);

/* Compress it. */
`php $miniJSTool $allJSFile $allJSFile`;

//-------------------------------- PROCESS CSS FILES ------------------------------ //

/* Define the themeRoot. */
$themeRoot  = $baseDir . '/www/theme/';

/* Iinclude config and lang file to get langs and themes. */
$config = new stdclass();
$config->programLink   = '-';
$config->productLink   = '-';
$config->projectLink   = '-';
$config->executionLink = '-';
$config->systemMode    = '';
$config->URAndSR       = '';
$config->systemScore   = '';
include $baseDir . '/config/config.php';

$lang = new stdclass();
$lang->productCommon   = '';
$lang->projectCommon   = '';
$lang->storyCommon     = '';
$lang->SRCommon        = '';
$lang->URCommon        = '';
$lang->productCommon   = '';
$lang->executionCommon = '';
include $baseDir . '/module/common/lang/zh-cn.php';
$langs  = array_keys($config->langs);
$themes = array_keys($lang->themes);

/* Create css files for every them and every lang. */
$zuiCode  = str_replace(array('/*!', '../fonts'), array('/*', '../zui/fonts'), file_get_contents($themeRoot . 'zui/css/min.css'));
foreach($langs as $lang)
{
    foreach($themes as $theme)
    {
        /* Common css files. */
        $cssCode  = $zuiCode;
        $cssCode .= file_get_contents($themeRoot  . 'default/style.css');

        /* Css file for current lang and current them. */
        if(file_exists($themeRoot . "lang/$lang.css")) $cssCode .= file_get_contents($themeRoot . "lang/$lang.css");
        if($theme != 'default')
        {
            $themCode = file_get_contents($themeRoot . $theme . '/style.css');
            $cssCode .= str_replace('./images', "../$theme/images", $themCode);
        }

        /* Combine them. */
        $cssFile = $themeRoot . "default/$lang.$theme.css";
        file_put_contents($cssFile, $cssCode);

        /* Compress it. */
        `php $miniCSSTool $cssFile $cssFile`;
    }
}
