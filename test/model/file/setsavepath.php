#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/file.class.php';
su('admin');

/**

title=测试 fileModel->setSavePath();
cid=1
pid=1

测试更新savePath >> /www/data/upload/1/

*/

$file = new fileTest();

r($file->setSavePathTest()) && p() && e('/www/data/upload/1/'); // 测试更新savePath