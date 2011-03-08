<?php
/**
 * This file is used to convert the old opt directory to ext.
 */
$modules = glob('../module/*');
foreach($modules as $module)
{
    $modulePath = realpath($module) . '/';
    $moduleOpt  = $modulePath . 'opt';
    $moduleExt  = $modulePath . 'ext';
    if(file_exists($moduleOpt))
    {
        echo "Processing $module ... ";
        $result = @rename($moduleOpt, $moduleExt);
        print($result ? ' done' : ' fail');
        echo "\n";
    }
}
