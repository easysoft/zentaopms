#!/usr/bin/env php
<?php

/**

title=测试 fileModel->setSavePath();
timeout=0
cid=0

- 测试更新savePath @/data/upload/1/

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';
su('admin');

$file = new fileTest();

global $app;
r($file->setSavePathTest()) && p() && e('/data/upload/1/'); // 测试更新savePath