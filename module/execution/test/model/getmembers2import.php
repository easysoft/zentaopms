#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
/**

title=测试executionModel->getMembers2ImportTest();
timeout=0
cid=16328

- 正常数据查询
 - 第user1条的account属性 @user1
 - 第user1条的role属性 @研发
- 无效数据查询 @无数据
- 正常数据查询统计 @2
- 无效数据查询 @0
- 无效数据查询 @0

*/

zenData('user')->gen(5);
su('admin');

$team = zenData('team');
$team->root->range('1');
$team->type->range('execution');
$team->account->range('user1,user2');
$team->role->range('研发,测试');
$team->days->range('5');
$team->hours->range('7');
$team->gen(2);

$executionList  = array('1', '2');
$accountList    = array('test1', 'test2');
$allAccountList = array('test1', 'user1', 'user2');
$count          = array(0, 1);

$executionTester = new executionModelTest();
r($executionTester->getMembers2ImportTest($executionList[0], $accountList, $count[0]))    && p('user1:account,role') && e('user1,研发'); // 正常数据查询
r($executionTester->getMembers2ImportTest($executionList[0], $allAccountList, $count[0])) && p()                     && e('无数据');     // 无效数据查询
r($executionTester->getMembers2ImportTest($executionList[0], $accountList, $count[1]))    && p()                     && e('2');          // 正常数据查询统计
r($executionTester->getMembers2ImportTest($executionList[0], $allAccountList, $count[1])) && p()                     && e('0');          // 无效数据查询
r($executionTester->getMembers2ImportTest($executionList[1], $allAccountList, $count[1])) && p()                     && e('0');          // 无效数据查询
