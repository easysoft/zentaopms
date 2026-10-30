#!/usr/bin/env php
<?php

/**

title=测试 fileModel::getUploadSavePath();
timeout=0
cid=0

- 合法 UID 返回可用的上传目录 @1
- 返回目录位于 uploadfiles 下 @1
- 返回目录以 UID 结尾 @1
- 空 UID 返回上传根目录 @1
- 路径穿越 UID 返回空路径 @0
- 编码后的路径穿越 UID 返回空路径 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$fileTest = new fileModelTest();
$uid      = 'upload_20261030_a';
$savePath = $fileTest->getUploadSavePathTest($uid);
$rootPath = $fileTest->getUploadSavePathTest('');

r($savePath !== '' && is_dir($savePath) ? '1' : '0') && p() && e('1');
r(strpos($savePath, DS . 'uploadfiles' . DS) !== false ? '1' : '0') && p() && e('1');
r(substr($savePath, -strlen($uid . DS)) === $uid . DS ? '1' : '0') && p() && e('1');
r($rootPath !== '' && is_dir($rootPath) ? '1' : '0') && p() && e('1');
r($fileTest->getUploadSavePathTest('../../escape')) && p() && e('0');
r($fileTest->getUploadSavePathTest('..%2F..%2Fescape')) && p() && e('0');

$uidPath = rtrim($savePath, DS);
if(is_dir($uidPath)) rmdir($uidPath);
