#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->createDefaultSprintTest();
cid=1
pid=1

创建默认迭代并返回ID >> 751

*/

$test = new executionTest();

$result = $test->createDefaultSprintTest(11);

r($result) && p('') && e('751'); // 创建默认迭代并返回ID

$db->restoreDB();