#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/file.class.php';
su('admin');

/**

title=测试 fileModel->setWebPath();
cid=1
pid=1

测试更新webPath >> /test/model/file/data/upload/1/

*/

$file = new fileTest();

r($file->setWebPathTest()) && p() && e('/test/model/file/data/upload/1/'); // 测试更新webPath