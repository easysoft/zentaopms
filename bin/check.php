<?php
/**
 * This file is used to check the language items and actions.
 */
/* Define an emtpty control class as the base class of every module. */
class control {}

/* set module root path and included the resource of group module. */
$moduleRoot = '../module/';
include $moduleRoot . '/group/lang/resource.php';

/* checking actions of every module. */
echo '-------------action checking-----------------' . "\n";
foreach(glob($moduleRoot . '*') as $modulePath)
{
    $moduleName  = basename($modulePath);
    if(strpos('install|upgrade|convert|common|misc', $moduleName) !== false) continue;
    $controlFile = $modulePath . '/control.php';
    if(file_exists($controlFile))
    {
        include $controlFile;
        if(class_exists($moduleName))
        {
            $class   = new ReflectionClass($moduleName);
            $methods = $class->getMethods();
            foreach($methods as $method)
            {
                $methodRef = new ReflectionMethod($method->class, $method->name);
                if($methodRef->isPublic() and strpos($method->name, '__') === false)
                {
                    $methodName = $method->name;
                    if(!isset($lang->resource->$moduleName->$methodName))
                    {
                        $methodName = strtolower($methodName);
                        if(!isset($lang->resource->$moduleName->$methodName))
                        {
                            echo $moduleName . "\t" . $methodName . " not in the list. \n";
                        }
                    }
                }
            }
        }
    }
}

/* checking actions of every module. */
echo '-------------lang checking-----------------' . "\n";
include '../config/config.php';
foreach(glob($moduleRoot . '*') as $modulePath)
{
    unset($lang);
    $moduleName   = basename($modulePath);
    $mainLangFile = $modulePath . '/lang/zh-cn.php';
    if(!file_exists($mainLangFile)) continue;
    $mainLines = file($mainLangFile);

    foreach($config->langs as $langKey => $langName)
    {
        if($langKey == 'zh-cn' or $langKey == 'zh-tw') continue;
        $langFile = $modulePath . '/lang/' . $langKey . '.php';
        if(!file_exists($langFile)) continue;
        $lines = file($langFile);
        foreach($mainLines as $lineNO => $line)
        {
            if(strpos($line, '$lang') === false)
            {
                //if($line != $lines[$lineNO]) echo $moduleName . ' ' . $langKey . ' ' . $lineNO . "\n";
            }
            else
            {
                list($mainKey, $mainValue) = explode('=', $line);
                list($key, $value) = explode('=', $lines[$lineNO]);
                if(trim($mainKey) != trim($key))
                {
                    $key = trim($key);
                    echo "$moduleName $langKey $mainKey $key  $lineNO \n";
                    break;
                }
            }
        }
    }
}
?>
