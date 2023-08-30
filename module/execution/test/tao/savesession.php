#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
zdTable('user')->gen(5);
su('admin');

/**

title=测试 executionModel->saveSession();
timeout=0
cid=1

*/

$executionTester = new executionTest();
r($executionTester->saveSessionTest(1)) && p() && e('1'); // 测试保存执行ID
