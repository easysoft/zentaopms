#!/usr/bin/env php
<?php

/**

title=测试 fileModel->getImageSize();
cid=0

- 传入空对象。 @0
- 检查图片大小。
 -  @100
 - 属性1 @150
 - 属性mime @image/png
- 文件不存在。
 -  @0
 - 属性1 @0
 - 属性2 @png

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$imgFile    = dirname(__FILE__) . '/img.png';
$imgContent = base64_decode('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');
file_put_contents($imgFile, $imgContent);

$file = new stdclass();
$file->realPath  = $imgFile;
$file->extension = 'png';

global $tester;
$fileModel = $tester->loadModel('file');
$fileModel->config->file->storageType = 'fs';

r($fileModel->getImageSize(new stdclass())) && p() && e('0'); //传入空对象。
r($fileModel->getImageSize($file))          && p('0,1,mime') && e('100,150,image/png'); //检查图片大小。

$file->realPath  = dirname(__FILE__) . '/img1.png';
r($fileModel->getImageSize($file)) && p('0,1,2') && e('0,0,png'); //文件不存在。

unlink($imgFile);