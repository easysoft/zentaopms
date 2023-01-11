#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$team = zdTable('team');
$team->root->range('3-5');
$team->type->range('execution');
$team->account->range('admin,user1,user2,user3,user4');
$team->role->range('研发,测试{4}');
$team->days->range('5');
$team->hours->range('7');
$team->gen(10);

/**

title=测试executionModel->getMembersByIdListTest();
cid=1
pid=1

批量查询敏捷执行team >> 3,admin,admin
批量查询瀑布执行team >> 4,user1,用户1
批量查询看板执行team >> 5,admin,admin
批量查询执行team统计 >> 3

*/

$executionIDList = array(3, 4, 5);
$count           = array('0','1');

$executionTester = new executionTest();
r($executionTester->getMembersByIdListTest($executionIDList, $count[0])[$executionIDList[0]]) && p('0:root,account,realname') && e('3,admin,admin'); // 批量查询敏捷执行team
r($executionTester->getMembersByIdListTest($executionIDList, $count[0])[$executionIDList[1]]) && p('0:root,account,realname') && e('4,user1,用户1'); // 批量查询瀑布执行team
r($executionTester->getMembersByIdListTest($executionIDList, $count[0])[$executionIDList[2]]) && p('0:root,account,realname') && e('5,admin,admin'); // 批量查询看板执行team
r($executionTester->getMembersByIdListTest($executionIDList, $count[1]))                      && p()                          && e('3');             // 批量查询执行team统计
