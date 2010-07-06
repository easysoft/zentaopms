#!/usr/bin/env php
<?php
$langType = $argv[1];
$langDesc = $argv[2];
if(empty($langType)) die('lang') . "\n";
foreach(glob('../module/*') as $moduleName)
{
    $moduleLangPath  = realpath($moduleName) . '/lang/';
    $defaultLangFile = $moduleLangPath . 'zh-cn.php';
    $targetLangFile  = $moduleLangPath . $langType . '.php';
    if(!file_exists($defaultLangFile)) continue;

    $defaultLang = file_get_contents($defaultLangFile);
    $targetLang  = str_replace('zh-cn', $langDesc, $defaultLang);
    file_put_contents($targetLangFile, $targetLang);
}
?>
