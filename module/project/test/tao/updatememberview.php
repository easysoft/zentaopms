#!/usr/bin/env php
<?php

/**

title=测试 projectTao::updateMemberView();
timeout=0
cid=0

- 执行projectTest模块的updateMemberViewTest方法，参数是1, array  @TABLE_NOT_EXISTS
- 执行projectTest模块的updateMemberViewTest方法，参数是1, array  @TABLE_NOT_EXISTS
- 执行projectTest模块的updateMemberViewTest方法，参数是1, array  @TABLE_NOT_EXISTS
- 执行projectTest模块的updateMemberViewTest方法，参数是1, array  @1
- 执行projectTest模块的updateMemberViewTest方法，参数是1, array  @TABLE_NOT_EXISTS

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,子冲刺1,子冲刺2,子冲刺3,关联产品1,关联产品2');
$project->code->range('project1,project2,project3,project4,project5,sprint1,sprint2,sprint3,product1,product2');
$project->type->range('project{5},sprint{3},kanban{2}');
$project->project->range('0{5},1,1,1,0,0');
$project->deleted->range('0');
$project->gen(10);

$team = zenData('team');
$team->root->range('6-8');
$team->type->range('execution');
$team->account->range('user1,user2,user3');
$team->gen(3);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,test1,test2,test3,test4,test5');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,测试1,测试2,测试3,测试4,测试5');
$user->deleted->range('0');
$user->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->deleted->range('0');
$product->vision->range('rnd');
$product->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-3');
$projectProduct->product->range('1-3');
$projectProduct->gen(3);

$userview = zenData('userview');
$userview->gen(0);

$usergroup = zenData('usergroup');
$usergroup->gen(0);

$group = zenData('group');
$group->gen(0);

$action = zenData('action');
$action->gen(0);

$history = zenData('history');
$history->gen(0);

su('admin');

$projectTest = new projectTest();

r($projectTest->updateMemberViewTest(1, array('admin', 'user1'), array())) && p() && e('TABLE_NOT_EXISTS');
r($projectTest->updateMemberViewTest(1, array(), array('admin' => 'admin', 'user1' => 'user1'))) && p() && e('TABLE_NOT_EXISTS');
r($projectTest->updateMemberViewTest(1, array('admin', 'user2'), array('user1' => 'user1'))) && p() && e('TABLE_NOT_EXISTS');
r($projectTest->updateMemberViewTest(1, array('admin'), array('admin' => 'admin'))) && p() && e('1');
$_POST['removeExecution'] = 'yes';
r($projectTest->updateMemberViewTest(1, array('admin'), array('user1' => 'user1'))) && p() && e('TABLE_NOT_EXISTS');