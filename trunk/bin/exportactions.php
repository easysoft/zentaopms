<?php
class control {}
$moduleRoot = rtrim($argv[1], '/') . '/';

foreach(glob($moduleRoot . '*') as $modulePath)
{
    $moduleName  = basename($modulePath);
    $controlFile = $modulePath . '/control.php';
    if(file_exists($controlFile))
    {
        include $controlFile;
        $lines = explode("\n", file_get_contents($controlFile));
        if(class_exists($moduleName))
        {
            $class   = new ReflectionClass($moduleName);
            $methods = $class->getMethods();
            foreach($methods as $method)
            {
                $methodRef = new ReflectionMethod($method->class, $method->name);
                if($methodRef->isPublic() and strpos($method->name, '__') === false)
                {
                    echo "\$lang['action']['$moduleName']['$method->name'] = '$method->name';\n";
                }
            }
            echo "\n";
        }
    }
}
?>


