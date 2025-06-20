#!/usr/bin/env php
<?php
/**

title=测试 fileModel->printFile();
timeout=0
cid=0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';
su('admin');

zenData('file')->gen(5);

$fileIdList     = range(1, 5);
$methodList     = array('', 'edit', 'view');
$showDeleteList = array(true, false);
$showEdit       = array(true, false);

$fileTester = new fileTest();
