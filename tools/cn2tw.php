#!/usr/bin/env php
<?php
$langType = 'zh-tw';
$langDesc = 'zh-tw';
if(empty($langType)) die('lang') . "\n";
foreach(array('../module/') as $subModuleRoot)
{
    foreach(glob($subModuleRoot . '*') as $moduleName)
    {
        $realModulePath  = realpath($moduleName);
        $moduleLangPath  = $realModulePath . '/lang/';
        $defaultLangFile = $moduleLangPath . 'zh-cn.php';
        $targetLangFile  = $moduleLangPath . $langType . '.php';
        if(file_exists($defaultLangFile))
        {
            system("cconv -f utf-8 -t UTF8-TW $defaultLangFile > $targetLangFile");
            $defaultLang = file_get_contents($targetLangFile);
            $targetLang  = str_replace('zh-cn', $langDesc, $defaultLang);
            file_put_contents($targetLangFile, $targetLang);
        }

        $extModuleLangPath  = $realModulePath . '/ext/lang/zh-cn/*.php';
        foreach(glob($extModuleLangPath) as $extModuleLang)
        {
            $fileName = basename($extModuleLang);
            if(!is_dir("{$realModulePath}/ext/lang/{$langType}/")) `mkdir -p {$realModulePath}/ext/lang/{$langType}/`;
            $targetLangFile  = $realModulePath . "/ext/lang/{$langType}/" . $fileName;

            system("cconv -f utf-8 -t UTF8-TW $extModuleLang > $targetLangFile");
            $defaultLang = file_get_contents($targetLangFile);
            $targetLang  = str_replace('zh-cn', $langDesc, $defaultLang);
            file_put_contents($targetLangFile, $targetLang);
        }
    }
}
?>
