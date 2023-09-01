#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$team = zdTable('team');
$team->root->range('1');
$team->type->range('execution');
$team->account->range('user1,user2');
$team->role->range('研发,测试');
$team->days->range('5');
$team->hours->range('7');
$team->gen(2);

/**

title=测试executionModel->getMembers2ImportTest();
timeout=0
cid=1

*/

$executionID    = '1';
$accountList    = array('test1', 'test2');
$allAccountList = array('test1', 'user1', 'user2');
$count          = array(0, 1);

$executionTester = new executionTest();
r($executionTester->getMembers2ImportTest($executionID, $accountList, $count[0]))    && p('user1:account,role') && e('user1,研发'); // 正常数据查询
r($executionTester->getMembers2ImportTest($executionID, $allAccountList, $count[0])) && p()                     && e('无数据');     // 无效数据查询
r($executionTester->getMembers2ImportTest($executionID, $accountList, $count[1]))    && p()                     && e('2');          // 正常数据查询统计
