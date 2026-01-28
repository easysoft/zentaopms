#!/usr/bin/env php
<?php

/**

title=测试executionModel->manageMembersTest();
timeout=0
cid=16355

- 获取团队成员user1
 - 属性root @3
 - 属性type @execution
 - 属性account @user1
- 获取团队成员user2
 - 属性root @3
 - 属性type @execution
 - 属性account @user2
- 获取团队成员admin
 - 属性root @3
 - 属性type @execution
 - 属性account @admin
- 团队人员管理统计 @3

*/
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
zenData('projectproduct')->gen(0);
zenData('product')->gen(0);
su('admin');

$executionID = 3;
$accounts    = array('user1', 'user2', 'admin');
$roles       = array('研发', '研发', '研发');
$hours       = array('3.0', '6.0', '7.0');
$limited     = array('yes', 'no', 'yes');
$days        = array('17', '27', '127');

$manageMembers[0] = array('role' => $roles[0], 'hours' => $hours[0], 'account' => $accounts[0], 'limited' => $limited[0], 'days' => $days[0], 'root' => $executionID, 'type' => 'execution');
$manageMembers[1] = array('role' => $roles[1], 'hours' => $hours[1], 'account' => $accounts[1], 'limited' => $limited[1], 'days' => $days[1], 'root' => $executionID, 'type' => 'execution');
$manageMembers[2] = array('role' => $roles[2], 'hours' => $hours[2], 'account' => $accounts[2], 'limited' => $limited[2], 'days' => $days[2], 'root' => $executionID, 'type' => 'execution');
$count            = array(0, 1);

$execution = new executionModelTest();
r($execution->manageMembersTest($executionID, $count[0], $manageMembers)[0]) && p('root,type,account') && e('3,execution,user1'); // 获取团队成员user1
r($execution->manageMembersTest($executionID, $count[0], $manageMembers)[1]) && p('root,type,account') && e('3,execution,user2'); // 获取团队成员user2
r($execution->manageMembersTest($executionID, $count[0], $manageMembers)[2]) && p('root,type,account') && e('3,execution,admin'); // 获取团队成员admin
r($execution->manageMembersTest($executionID, $count[1], $manageMembers))    && p()                    && e('3');                 // 团队人员管理统计
