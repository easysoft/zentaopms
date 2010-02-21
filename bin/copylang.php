#!/usr/bin/env php
<?php
$lang = $argv[1];
foreach(glob('../module/*') as $moduleName)
{
    $moduleLangPath  = realpath($moduleName) . '/lang/';
    $defaultLangFile = $moduleLangPath . 'zh-cn.php';
    $targetLangFile  = $moduleLangPath . $lang . '.php';
    $defaultLang     = file_get_contents($defaultLangFile);
    echo $defaultLang;
}
?>
