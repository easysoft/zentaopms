#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->unlinkCasesTest();
cid=1
pid=1

敏捷执行解除关联用例 >> 0
瀑布执行解除关联用例 >> 0
看板执行解除关联用例 >> 0

*/

$executionIDList = array('101', '131', '161');
$products        = array('1', '43', '68');
$stories         = array('2', '170', '270');
$count           = array('0','1');

$execution = new executionTest();
r($execution->unlinkCasesTest($executionIDList[0], $products[0], $stories[0])) && p() && e('0'); // 敏捷执行解除关联用例
r($execution->unlinkCasesTest($executionIDList[1], $products[1], $stories[1])) && p() && e('0'); // 瀑布执行解除关联用例
r($execution->unlinkCasesTest($executionIDList[2], $products[2], $stories[2])) && p() && e('0'); // 看板执行解除关联用例

$db->restoreDB();