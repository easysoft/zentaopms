<?php
chdir(dirname(dirname(dirname(__FILE__))));

include './framework/router.class.php';
include './framework/control.class.php';
include './framework/model.class.php';
include './framework/helper.class.php';

$app         = router::createApp('pms', dirname(dirname(dirname(__FILE__))), 'router');
$cacheConfig = $app->config->cache;
if(!$cacheConfig->enable && !$cacheConfig->enableFullPage)
{
    echo 'Cache is disabled.', PHP_EOL;
    exit;
}

function traverseDirectory($directory, &$cleanNum)
{
    $files = glob($directory . DS . '*.cache');

    foreach($files as $file)
    {
        $result = viewFileContent($file);
        if(!$result) $cleanNum ++;
    }

    $subdirectories = glob($directory . DS . '*', GLOB_ONLYDIR);

    foreach($subdirectories as $subdirectory) traverseDirectory($subdirectory, $cleanNum);
}

function isExpired($payload)
{
    if(is_null($payload['time'])) return false;

    return time() > $payload['time'];
}

function viewFileContent($file)
{
    $content = file_get_contents($file);
    $content = unserialize($content);
    if(isExpired($content))
    {
        unlink($file);
        return false;
    }
    return true;
}

$cleanNum = 0;
traverseDirectory(rtrim($app->getCacheRoot(), DS), $cleanNum);

echo "Cleaned {$cleanNum} cache files.", PHP_EOL;
