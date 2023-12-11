#!/usr/bin/env php
<?php

/**

title=测试 fileModel->pasteImage();
cid=0

- 不传入任何数据。 @0
- 传入有图片信息的数据。 @<img src="/file-read-1.png" />
- 传入无图片信息的数据。 @12<script>3</script>4
- 传入无图片信息的数据，并开启安全参数。 @124
- 检查SESSION信息。 @1
- 检查图片大小。属性size @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('file')->gen(0);

global $tester;
$fileModel = $tester->loadModel('file');
$fileModel->config->webRoot = '/';
$fileModel->config->requestType = 'PATH_INFO';

$uid         = '1234';
$withImgData = '<img src="data:image/png;base64,' . base64_encode('1234') . '" />';
$noImgData   = '12<script>3</script>4';

r($fileModel->pasteImage('', $uid))               && p() && e('0');                              //不传入任何数据。
r($fileModel->pasteImage($withImgData, $uid))     && p() && e('<img src="/file-read-1.png" />'); //传入有图片信息的数据。
r($fileModel->pasteImage($noImgData, $uid))       && p() && e('12<script>3</script>4');          //传入无图片信息的数据。
r($fileModel->pasteImage($noImgData, $uid, true)) && p() && e('124');                            //传入无图片信息的数据，并开启安全参数。

r(array_pop($_SESSION['album'][$uid])) && p()       && e('1'); //检查SESSION信息。
r((array)$fileModel->getById(1))       && p('size') && e('4'); //检查图片大小。
