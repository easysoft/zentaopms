#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
/**

title=测试executionModel->getMembersByIdListTest();
timeout=0
cid=16329

- 批量查询敏捷执行team
 - 第admin条的root属性 @3
 - 第admin条的account属性 @admin
 - 第admin条的realname属性 @admin
- 批量查询瀑布执行team
 - 第user1条的root属性 @4
 - 第user1条的account属性 @user1
 - 第user1条的realname属性 @用户1
- 批量查询看板执行team
 - 第admin条的root属性 @5
 - 第admin条的account属性 @admin
 - 第admin条的realname属性 @admin
- 批量查询执行team统计 @3
- 批量查询执行team统计
 - 第user3条的root属性 @5
 - 第user3条的account属性 @user3
 - 第user3条的realname属性 @用户3

*/

zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$team = zenData('team');
$team->root->range('3-5');
$team->type->range('execution');
$team->account->range('admin,user1,user2,user3,user4');
$team->role->range('研发,测试{4}');
$team->days->range('5');
$team->hours->range('7');
$team->gen(10);

$executionIDList = array(3, 4, 5);
$count           = array('0','1', '2');

$executionTester = new executionTest();
r($executionTester->getMembersByIdListTest($executionIDList, $count[0])[$executionIDList[0]]) && p('admin:root,account,realname') && e('3,admin,admin'); // 批量查询敏捷执行team
r($executionTester->getMembersByIdListTest($executionIDList, $count[0])[$executionIDList[1]]) && p('user1:root,account,realname') && e('4,user1,用户1'); // 批量查询瀑布执行team
r($executionTester->getMembersByIdListTest($executionIDList, $count[0])[$executionIDList[2]]) && p('admin:root,account,realname') && e('5,admin,admin'); // 批量查询看板执行team
r($executionTester->getMembersByIdListTest($executionIDList, $count[1]))                      && p()                              && e('3');             // 批量查询执行team统计
r($executionTester->getMembersByIdListTest($executionIDList, $count[2])[$executionIDList[2]]) && p('user3:root,account,realname') && e('5,user3,用户3'); // 批量查询执行team统计
