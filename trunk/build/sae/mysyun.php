<?php
$appRoot = dirname(dirname(dirname(__FILE__))) . '/' ;

$sourceHtaccessFile = $appRoot . 'htaccess';
$targetHtaccessFile = $appRoot . '.htaccess';
if(file_exists($sourceHtaccessFile))
{
    $targetHtaccessCode = str_replace('index.php', $config->webRoot . 'index.php', file_get_contents($sourceHtaccessFile));
    file_put_contents($targetHtaccessFile, $targetHtaccessCode);
}

$tmpRoot = $appRoot . '/tmp/';
if(!is_dir($tmpRoot))
{
    mkdir($tmpRoot, 0777);
    mkdir($tmpRoot . 'cache', 0777);
    mkdir($tmpRoot . 'extension', 0777);
    mkdir($tmpRoot . 'log', 0777);
    mkdir($tmpRoot . 'model', 0777);
    mkdir($tmpRoot . 'svn', 0777);
}

$dataRoot = $appRoot . 'data/';
if(!is_dir($dataRoot))
{
    mkdir($dataRoot, 0777);
    mkdir($dataRoot . 'upload', 0777);
}
