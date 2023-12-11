#!/usr/bin/env php
<?php

/**

title=测试 fileModel->setWebPath();
cid=0

- 测试更新webPath @/data/upload/1/

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

$file = new fileTest();

r($file->setWebPathTest()) && p() && e('/data/upload/1/'); // 测试更新webPath
