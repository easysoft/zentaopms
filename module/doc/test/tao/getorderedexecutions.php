#!/usr/bin/env php
<?php
/**

title=测试 docModel->getOrderedExecutions();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('project')->config('execution')->gen(20);
zdTable('user')->gen(5);
su('admin');

$appends = array(0, 101);

$docTester = new docTest();
r($docTester->getOrderedExecutionsTest($appends[0])) && p('0:102') && e('敏捷项目1 / 迭代6'); // 获取系统中已排序的执行
r($docTester->getOrderedExecutionsTest($appends[1])) && p('0:102') && e('敏捷项目1 / 迭代6'); // 获取系统中包括ID=101已排序的执行
