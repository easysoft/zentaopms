#!/usr/bin/env php
<?php
$langType = 'zh-tw';
$langDesc = 'zh-tw';
if(empty($langType)) die('lang') . "\n";
foreach(glob('../module/*') as $moduleName)
{
    $moduleLangPath  = realpath($moduleName) . '/lang/';
    $defaultLangFile = $moduleLangPath . 'zh-cn.php';
    $targetLangFile  = $moduleLangPath . $langType . '.php';
    if(!file_exists($defaultLangFile)) continue;

    system("cconv -f utf-8 -t UTF8-TW $defaultLangFile > $targetLangFile");
    $defaultLang = file_get_contents($targetLangFile);
    $targetLang  = str_replace('zh-cn', $langDesc, $defaultLang);
    file_put_contents($targetLangFile, $targetLang);
}
?>
