#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

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

/**

title=测试executionModel->unlinkMember();
timeout=0
cid=16373

- 敏捷执行解除团队成员
 - 第0条的account属性 @user4
 - 第0条的role属性 @测试
- 敏捷执行解除团队成员后统计 @1
- 瀑布执行解除团队成员
 - 第0条的account属性 @user2
 - 第0条的role属性 @研发
- 看板执行解除团队成员第0条的account属性 @0
第0条的role属性 @0
- 看板执行解除团队成员后统计 @0

*/

$accountList     = array('user1', 'user2', 'user3');
$executionIDList = array('3', '4', '5');
$count           = array(0, 1);

$execution = new executionModelTest();
r($execution->unlinkMemberTest($executionIDList[0], $accountList[0], $count[0])) && p('0:account,role') && e('user4,测试');  // 敏捷执行解除团队成员
r($execution->unlinkMemberTest($executionIDList[0], $accountList[0], $count[1])) && p()                 && e('1');           // 敏捷执行解除团队成员后统计
r($execution->unlinkMemberTest($executionIDList[1], $accountList[1], $count[0])) && p('0:account,role') && e('user2,研发');  // 瀑布执行解除团队成员
r($execution->unlinkMemberTest($executionIDList[2], $accountList[2], $count[0])) && p('0:account,role') && e('0');           // 看板执行解除团队成员
r($execution->unlinkMemberTest($executionIDList[2], $accountList[2], $count[1])) && p()                 && e('0');           // 看板执行解除团队成员后统计