#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->linkCasesTest();
cid=1
pid=1

敏捷执行关联用例 >> 101,1,1
瀑布执行关联用例 >> 131,43,169
看板执行关联用例 >> 161,68,269
敏捷执行关联用例统计 >> 4
瀑布执行关联用例统计 >> 4
看板执行关联用例统计 >> 4

*/

$executionIDList = array('101', '131', '161');
$stories         = array('2', '170', '270');
$products        = array('1', '43', '68');
$count           = array('0','1');

$execution = new executionTest();
r($execution->linkCasesTest($executionIDList[0], $count[0], $products[0], $stories[0])) && p('0:project,product,case') && e('101,1,1');    // 敏捷执行关联用例
r($execution->linkCasesTest($executionIDList[1], $count[0], $products[1], $stories[1])) && p('0:project,product,case') && e('131,43,169'); // 瀑布执行关联用例
r($execution->linkCasesTest($executionIDList[2], $count[0], $products[2], $stories[2])) && p('0:project,product,case') && e('161,68,269'); // 看板执行关联用例
r($execution->linkCasesTest($executionIDList[0], $count[1], $products[0], $stories[0])) && p()                         && e('4');          // 敏捷执行关联用例统计
r($execution->linkCasesTest($executionIDList[1], $count[1], $products[1], $stories[1])) && p()                         && e('4');          // 瀑布执行关联用例统计
r($execution->linkCasesTest($executionIDList[2], $count[1], $products[2], $stories[2])) && p()                         && e('4');          // 看板执行关联用例统计

$db->restoreDB();