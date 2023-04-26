#!/usr/bin/env php
<?php
/* Set the extpath and cconv binary. */
if(empty($argv[1])) die("Must give the module path.\n");
$extPath = $argv[1];

foreach(glob("$extPath/extension/xuan/*") as $moduleName)
{
    /* Convert the main lang file. */
    $moduleLangPath    = realpath($moduleName) . '/lang/';
    $defaultLangFile   = $moduleLangPath . 'en.php';
    $targetDELangFile  = $moduleLangPath . 'de.php';
    $targetFRLangFile  = $moduleLangPath . 'fr.php';
    if(file_exists($defaultLangFile))
    {
        system("cp -r $defaultLangFile $targetDELangFile");
        system("cp -r $defaultLangFile $targetFRLangFile");
    }

    /* Convert the extened lang file. */
    foreach(glob("$moduleName/ext/lang/en/*.php") as $extLangFile)
    {
        $pathOfDE    = dirname(dirname($extLangFile)) . "/de";
        $pathOfFR    = dirname(dirname($extLangFile)) . "/fr";
        $extFileName = basename($extLangFile);
        if(!is_dir($pathOfDE)) mkdir($pathOfDE);
        if(!is_dir($pathOfFR)) mkdir($pathOfFR);
        system("cp -r $extLangFile $pathOfDE/$extFileName");
        system("cp -r $extLangFile $pathOfFR/$extFileName");
    }
}
