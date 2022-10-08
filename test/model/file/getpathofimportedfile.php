#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/file.class.php';
su('admin');

/**

title=测试 fileModel->getPathOfImportedFile();
cid=1
pid=1

获取导入路径 >> tmp/import

*/

$file = new fileTest();

r($file->getPathOfImportedFileTest()) && p() && e('tmp/import'); // 获取导入路径