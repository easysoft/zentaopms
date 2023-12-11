#!/usr/bin/env php
<?php

/**

title=测试 fileModel->sendDownHeader();
cid=0

- 根据内容下载。 @test
- 文件路径是非法路径。 @0
- 根据文件路径下载。 @test1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$fileModel = $tester->loadModel('file');
$_SERVER['HTTP_USER_AGENT'] = 'MSIE';

$content = '';
try
{
    $fileModel->sendDownHeader('test.txt', 'txt', 'test', 'content');
}
catch(EndResponseException $e)
{
    $content = $e->getContent();
}
r($content) && p() && e('test'); //根据内容下载。

$content      = '';
$downloadFile = dirname(__FILE__) . '/download.txt';
file_put_contents($downloadFile, 'test1');
try
{
    r($fileModel->sendDownHeader('test.txt', 'txt', 'test.txt', 'file'))    && p() && e('0');      //文件路径是非法路径。
    r($fileModel->sendDownHeader('test.txt', 'txt', $downloadFile, 'file')) && p() && e('test1');  //根据文件路径下载。
}
catch(EndResponseException $e){}

unlink($downloadFile);
