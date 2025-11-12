#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getProductMembersForCreate();
timeout=0
cid=0

- 执行bugTest模块的getProductMembersForCreateTest方法，参数是$bug1 属性admin @A:管理员
- 执行bugTest模块的getProductMembersForCreateTest方法，参数是$bug2 属性admin @A:管理员
- 执行bugTest模块的getProductMembersForCreateTest方法，参数是$bug3 属性admin @A:管理员
- 执行bugTest模块的getProductMembersForCreateTest方法，参数是$bug4 属性admin @管理员
- 执行bugTest模块的getProductMembersForCreateTest方法，参数是$bug5 属性admin @管理员

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 准备user测试数据
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->role->range('admin,dev{5},qa{4}');
$user->deleted->range('0{8},1{2}');
$user->gen(10);

// 准备project测试数据(用于execution和project)
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,执行1,执行2,执行3');
$project->type->range('project{2},sprint{3}');
$project->status->range('doing');
$project->deleted->range('0');
$project->gen(5);

// 准备team测试数据
$team = zenData('team');
$team->id->range('1-15');
$team->root->range('1{3},2{3},3{3},4{3},5{3}');
$team->type->range('[project]{6},[execution]{9}');
$team->account->range('admin,user1,user2,admin,user3,user4,admin,user5,user6,admin,user7,user8,admin,user8,user9');
$team->gen(15);

// 准备product测试数据
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->type->range('normal{2},branch');
$product->deleted->range('0');
$product->gen(3);

// 准备productplan测试数据(用于获取产品成员)
$plan = zenData('productplan');
$plan->id->range('1-5');
$plan->product->range('1-3');
$plan->branch->range('0{3},1,2');
$plan->gen(5);

su('admin');

global $tester;
$bugTest = new bugZenTest();

// 获取bug zen实例并设置view中的users
$bugZenInstance = $bugTest->getBugZenInstance();
$bugZenInstance->view = new stdclass();
$bugZenInstance->view->users = array('admin' => '管理员', 'user1' => '用户1');

// 测试场景1:allUsers为true,获取所有开发优先的未关闭用户
$bug1 = new stdclass();
$bug1->allUsers = true;
$bug1->productID = 1;
$bug1->branch = 0;
$bug1->executionID = 0;
$bug1->projectID = 0;
r($bugTest->getProductMembersForCreateTest($bug1)) && p('admin') && e('A:管理员');

// 测试场景2:有executionID,获取执行团队成员
$bug2 = new stdclass();
$bug2->allUsers = false;
$bug2->productID = 1;
$bug2->branch = 0;
$bug2->executionID = 3;
$bug2->projectID = 0;
r($bugTest->getProductMembersForCreateTest($bug2)) && p('admin') && e('A:管理员');

// 测试场景3:有projectID,获取项目团队成员
$bug3 = new stdclass();
$bug3->allUsers = false;
$bug3->productID = 1;
$bug3->branch = 0;
$bug3->executionID = 0;
$bug3->projectID = 1;
r($bugTest->getProductMembersForCreateTest($bug3)) && p('admin') && e('A:管理员');

// 测试场景4:只有productID和branch,获取产品成员
$bug4 = new stdclass();
$bug4->allUsers = false;
$bug4->productID = 1;
$bug4->branch = 0;
$bug4->executionID = 0;
$bug4->projectID = 0;
r($bugTest->getProductMembersForCreateTest($bug4)) && p('admin') && e('管理员');

// 测试场景5:各ID都为0,使用view中的users
$bug5 = new stdclass();
$bug5->allUsers = false;
$bug5->productID = 0;
$bug5->branch = 0;
$bug5->executionID = 0;
$bug5->projectID = 0;
r($bugTest->getProductMembersForCreateTest($bug5)) && p('admin') && e('管理员');