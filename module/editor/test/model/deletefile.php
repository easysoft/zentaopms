#!/usr/bin/env php
<?php

/**

title=测试 editorModel::deleteFile();
timeout=0
cid=16244

- 正常删除自定义扩展文件 @1
- 拒绝删除自定义扩展目录外的文件 @1
- 拒绝删除目录 @1
- 拒绝删除不存在的文件 @1
- 自定义扩展目录不存在时拒绝删除 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$editor        = new editorModelTest();
$extensionRoot = $editor->instance->app->getExtensionRoot();
$customPath  = $extensionRoot . 'custom' . DS . 'deletefile-' . getmypid();
$outsidePath = $extensionRoot . 'deletefile-outside-' . getmypid() . '.txt';
$customFile  = $customPath . DS . 'custom.txt';
$directory   = $customPath . DS . 'directory';
$missingFile = $customPath . DS . 'missing.txt';
$originalApp = $editor->instance->app;

try
{
    if(!is_dir($directory)) mkdir($directory, 0777, true);
    file_put_contents($customFile, 'custom');

    r((int)($editor->deleteFileTest($customFile) && !file_exists($customFile))) && p() && e('1');

    file_put_contents($outsidePath, 'outside');
    $traversalPath = $customPath . DS . '..' . DS . '..' . DS . basename($outsidePath);
    r((int)(!$editor->deleteFileTest($traversalPath) && file_exists($outsidePath))) && p() && e('1');

    r((int)(!$editor->deleteFileTest($directory) && is_dir($directory))) && p() && e('1');

    r((int)!$editor->deleteFileTest($missingFile)) && p() && e('1');

    $editor->instance->app = new class
    {
        public function getExtensionRoot(): string
        {
            return '/path/that/does/not/exist/';
        }
    };
    r((int)(!$editor->deleteFileTest($outsidePath) && file_exists($outsidePath))) && p() && e('1');
}
finally
{
    $editor->instance->app = $originalApp;
    foreach(array($customFile, $outsidePath) as $file) if(file_exists($file)) unlink($file);
    if(is_dir($directory)) rmdir($directory);
    if(is_dir($customPath)) rmdir($customPath);
}
