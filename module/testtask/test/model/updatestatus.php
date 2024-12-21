#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

zenData('testtask')->gen(5);

su('admin');

/**

title=测试 testtaskModel->updateStatus();
cid=1
pid=1

*/

$uid = uniqid();

$taskIdList = array(1, 2, 3, 4, 5, 10001, 0);
