#!/usr/bin/env php
<?php

/**

title=测试 biModel::downloadFile();
timeout=0
cid=15156

- 步骤1：空 URL 返回失败 @1
- 步骤2：非法 URL 返回失败 @1
- 步骤3：错误 JSON 文件返回失败 @1
- 步骤4：不存在的本地文件返回失败 @1
- 步骤5：本地文本文件下载成功 @1
- 步骤6：本地 ZIP 文件下载并解压成功 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$biTest = new biModelTest();

$sourceDir = sys_get_temp_dir() . '/bi_download_src_' . uniqid();
$saveDir   = sys_get_temp_dir() . '/bi_download_dst_' . uniqid() . '/';
mkdir($sourceDir, 0755, true);
mkdir($saveDir, 0755, true);

$textSource  = $sourceDir . '/source.txt';
$errorSource = $sourceDir . '/error.json';
$zipSource   = $sourceDir . '/source.zip';

file_put_contents($textSource, 'demo file');
file_put_contents($errorSource, '{"error":"failed"}');

if(class_exists('ZipArchive'))
{
    $zip = new ZipArchive();
    $zip->open($zipSource, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $zip->addFromString('test.bin', 'zip file');
    $zip->close();
}
else
{
    $zip = new PclZip($zipSource);
    $zip->create(array(array(PCLZIP_ATT_FILE_NAME => 'test.bin', PCLZIP_ATT_FILE_CONTENT => 'zip file')));
}

r($biTest->downloadFileTest('', $saveDir, 'source.txt') === false) && p() && e('1');
r($biTest->downloadFileTest('invalid-url', $saveDir, 'source.txt') === false) && p() && e('1');
r($biTest->downloadFileTest('file://' . $errorSource, $saveDir, 'source.txt') === false) && p() && e('1');
r($biTest->downloadFileTest('file://' . $sourceDir . '/missing.txt', $saveDir, 'source.txt') === false) && p() && e('1');
r($biTest->downloadFileTest('file://' . $textSource, $saveDir, 'source.txt') === true && file_exists($saveDir . 'source.txt')) && p() && e('1');
r($biTest->downloadFileTest('file://' . $zipSource, $saveDir, 'test.bin') === true && file_exists($saveDir . 'test.bin')) && p() && e('1');

foreach(array($saveDir . 'source.txt', $saveDir . 'source.zip', $saveDir . 'test.bin') as $file)
{
    if(file_exists($file)) unlink($file);
}
foreach(array($textSource, $errorSource, $zipSource) as $file)
{
    if(file_exists($file)) unlink($file);
}
if(is_dir($saveDir)) rmdir($saveDir);
if(is_dir($sourceDir)) rmdir($sourceDir);
