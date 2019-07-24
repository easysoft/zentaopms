#!/usr/bin/env php
<?php
/* Set the extpath and cconv binary. */
if(empty($argv[1])) die("Must give the module path.\n");
$extPath = $argv[1];

foreach(glob("$extPath/module/*") as $moduleName)
{
    /* Convert the main lang file. */
    $moduleLangPath  = realpath($moduleName) . '/lang/';
    $defaultLangFile = $moduleLangPath . 'en.php';
    $targetLangFile  = $moduleLangPath . 'de.php';
    if(file_exists($defaultLangFile)) system("cp -r $defaultLangFile $targetLangFile");

    /* Convert the extened lang file. */
    foreach(glob("$moduleName/ext/lang/en/*.php") as $extLangFile)
    {
        $pathOfTW    = dirname(dirname($extLangFile)) . "/de";
        $extFileName = basename($extLangFile);
        if(!is_dir($pathOfTW)) mkdir($pathOfTW);
        system("cp -r $extLangFile $pathOfTW/$extFileName");
    }
}
