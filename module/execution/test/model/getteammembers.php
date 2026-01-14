#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
/**

title=测试executionModel->getTeamMembersTest();
timeout=0
cid=16345

- 敏捷执行查看team
 - 第user1条的root属性 @3
 - 第user1条的type属性 @execution
 - 第user1条的userID属性 @2
- 瀑布执行查看team
 - 第user2条的root属性 @4
 - 第user2条的type属性 @execution
 - 第user2条的realname属性 @用户2
- 看板执行查看team
 - 第user3条的root属性 @5
 - 第user3条的type属性 @execution
 - 第user3条的realname属性 @用户3
- 敏捷执行team统计 @2
- 瀑布执行team统计 @1
- 看板执行team统计 @1

*/

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,waterfall,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$team = zenData('team');
$team->root->range('3-5');
$team->account->range('1-5')->prefix('user');
$team->role->range('研发{3},测试{2}');
$team->type->range('execution');
$team->join->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$team->gen(5);

zenData('user')->gen(5);
su('admin');

$executionIDList = array('3', '4', '5');
$count           = array(0, 1);

$execution = new executionModelTest();
r($execution->getTeamMembersTest($executionIDList[0], $count[0])) && p('user1:root,type,userID')   && e('3,execution,2');     // 敏捷执行查看team
r($execution->getTeamMembersTest($executionIDList[1], $count[0])) && p('user2:root,type,realname') && e('4,execution,用户2'); // 瀑布执行查看team
r($execution->getTeamMembersTest($executionIDList[2], $count[0])) && p('user3:root,type,realname') && e('5,execution,用户3'); // 看板执行查看team
r($execution->getTeamMembersTest($executionIDList[0], $count[1])) && p()                           && e('2');                 // 敏捷执行team统计
r($execution->getTeamMembersTest($executionIDList[1], $count[1])) && p()                           && e('1');                 // 瀑布执行team统计
r($execution->getTeamMembersTest($executionIDList[2], $count[1])) && p()                           && e('1');                 // 看板执行team统计
