#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
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

$team = zdTable('team');
$team->root->range('3-5');
$team->account->range('1-5')->prefix('user');
$team->role->range('研发{3},测试{2}');
$team->type->range('execution');
$team->join->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$team->gen(5);

zdTable('user')->gen(5);
su('admin');

/**

title=测试executionModel->manageMembersTest();
cid=1
pid=1

团队管理         >> 3,execution,user1
团队人员管理统计 >> 3

*/

$executionID = '3';
$realnames   = array('用户1', '用户2', 'admin');
$roles       = array('研发', '研发', '研发');
$hours       = array('3.0', '6.0', '7.0');
$accounts    = array('user1', 'user2', 'admin');
$limited     = array('yes', 'no', 'yes');
$days        = array('17', '27', '127');

$manageMembers = array('realnames' => $realnames, 'roles' => $roles, 'hours' => $hours, 'accounts' => $accounts, 'limited' => $limited, 'days' => $days);
$count         = array('0','1');

$execution = new executionTest();
r($execution->manageMembersTest($executionID, $count[0], $manageMembers)) && p('0:root,type,account') && e('3,execution,user1'); // 团队管理
r($execution->manageMembersTest($executionID, $count[1], $manageMembers)) && p()                      && e('3');                 // 团队人员管理统计
