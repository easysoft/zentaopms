#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getMembers2ImportTest();
cid=1
pid=1

敏捷执行关联用例 >> 101,1,1
瀑布执行关联用例 >> 131,43,169
看板执行关联用例 >> 161,68,269
敏捷执行关联用例统计 >> 4
瀑布执行关联用例统计 >> 4
看板执行关联用例统计 >> 4

*/

$executionID    = '101';
$accountList    = array('test7', 'test82');
$allAccountList = array('test82', 'po82', 'user92');
$count          = array('0','1');

$execution = new executionTest();
r($execution->getMembers2ImportTest($executionID, $accountList, $count[0]))    && p('po82:account,role') && e('po82,研发'); // 正常数据查询
r($execution->getMembers2ImportTest($executionID, $allAccountList, $count[0])) && p()                    && e('无数据');    // 无效数据查询
r($execution->getMembers2ImportTest($executionID, $accountList, $count[1]))    && p()                    && e('2');         // 正常数据查询统计
