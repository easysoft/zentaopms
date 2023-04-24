#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

/**

title=测试 fileModel->setWebPath();
cid=1
pid=1

测试更新webPath >> /test/model/file/data/upload/1/

*/

$file = new fileTest();

r($file->setWebPathTest()) && p() && e('/test/model/file/data/upload/1/'); // 测试更新webPath