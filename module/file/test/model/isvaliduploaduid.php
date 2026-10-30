#!/usr/bin/env php
<?php

/**

title=测试 fileModel::isValidUploadUID();
timeout=0
cid=0

- 字母数字下划线和连字符组成的 UID 有效 @1
- 空 UID 表示不使用子目录 @1
- 包含斜杠的 UID 无效 @0
- 包含点号的 UID 无效 @0
- 包含 URL 编码字符的 UID 无效 @0
- 包含中文的 UID 无效 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$fileTest = new fileModelTest();

r($fileTest->isValidUploadUIDTest('upload_20261030-A')) && p() && e('1');
r($fileTest->isValidUploadUIDTest('')) && p() && e('1');
r($fileTest->isValidUploadUIDTest('../upload')) && p() && e('0');
r($fileTest->isValidUploadUIDTest('upload.20261030')) && p() && e('0');
r($fileTest->isValidUploadUIDTest('upload%2Fescape')) && p() && e('0');
r($fileTest->isValidUploadUIDTest('上传UID')) && p() && e('0');
