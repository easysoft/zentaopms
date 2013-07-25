<?php
error_reporting(E_ALL);
$langType = empty($argv[1]) ? 'zh-cn' : $argv[1];
$modules = glob('../module/*');
$maxLength = 0;
foreach($modules as $modulePath)
{
    $moduleName = basename($modulePath);
    if(strlen($moduleName) > $maxLength) $maxLength = strlen($moduleName);
}

foreach($modules as $modulePath)
{
    $moduleName = basename($modulePath);
    if($moduleName == 'help' or $moduleName == 'editor') continue;
    $langFile = $modulePath . "/lang/$langType.php";
    if(!file_exists($langFile)) continue;
    include $langFile;

    $moduleTitle = '';
    if(isset($lang->$moduleName->common))
    {
        $moduleTitle = $lang->$moduleName->common;
    }

    echo "\$lang->editor->modules['$moduleName'] " . str_pad('', $maxLength - strlen($moduleName)) . "= '$moduleTitle';\n";

}
