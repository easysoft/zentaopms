#!/usr/bin/env php
<?php

/**

title=测试 fileModel->setSavePath();
cid=0

- 测试更新savePath @/www/data/upload/1/

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

$file = new fileTest();

global $app;
r($file->setSavePathTest()) && p() && e('/var/web/ztpms/www/data/upload/1/'); // 测试更新savePath
