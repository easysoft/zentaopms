#!/usr/bin/env php
<?php

/**

title=测试 buildZen::assignCreateData();
timeout=0
cid=15515

- 执行buildTest模块的assignCreateDataTest方法，参数是1, 0, 1, '' 属性productID @1
- 执行buildTest模块的assignCreateDataTest方法，参数是1, 11, 1, '' 属性executionID @11
- 执行buildTest模块的assignCreateDataTest方法，参数是0, 0, 1, '' 第products条的1属性 @产品1
- 执行buildTest模块的assignCreateDataTest方法，参数是1, 11, 1, '' 属性executionID @11
- 执行buildTest模块的assignCreateDataTest方法，参数是1, 0, 1, 'normal' 属性productID @1
- 执行buildTest模块的assignCreateDataTest方法，参数是1, 0, 1, '' 第users条的admin属性 @`A:管理员`
- 执行buildTest模块的assignCreateDataTest方法，参数是1, 0, 1, '' 属性productID @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{3},branch{1},platform{1}');
$product->status->range('normal{4},closed{1}');
$product->gen(5);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project{3},sprint{2}');
$project->status->range('doing{3},wait{1},closed{1}');
$project->parent->range('0');
$project->path->range('`,1,`,`,2,`,`,3,`,`,4,`,`,5,`');
$project->grade->range('1');
$project->model->range('scrum{2},waterfall{2},kanban{1}');
$project->gen(5);

$execution = zenData('project');
$execution->id->range('11-15');
$execution->project->range('1-3');
$execution->name->range('迭代1,迭代2,迭代3,迭代4,迭代5');
$execution->type->range('sprint{3},stage{2}');
$execution->status->range('doing{3},wait{1},closed{1}');
$execution->parent->range('1-3');
$execution->path->range('`,1,11,`,`,1,12,`,`,2,13,`,`,2,14,`,`,3,15,`');
$execution->grade->range('2');
$execution->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-15');
$projectProduct->product->range('1-5');
$projectProduct->gen(15);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->deleted->range('0{9},1{1}');
$user->gen(10);

zenData('build')->gen(0);

global $tester;
$tester->app->rawModule   = 'build';
$tester->app->rawMethod   = 'create';
$tester->app->tab         = 'project';
$tester->app->moduleName  = 'build';
$tester->app->methodName  = 'create';
$_SESSION['project']      = 1;

su('admin');

$buildTest = new buildZenTest();

r($buildTest->assignCreateDataTest(1, 0, 1, '')) && p('productID') && e('1');
r($buildTest->assignCreateDataTest(1, 11, 1, '')) && p('executionID') && e('11');
r($buildTest->assignCreateDataTest(0, 0, 1, '')) && p('products:1') && e('产品1');
r($buildTest->assignCreateDataTest(1, 11, 1, '')) && p('executionID') && e('11');
r($buildTest->assignCreateDataTest(1, 0, 1, 'normal')) && p('productID') && e('1');
r($buildTest->assignCreateDataTest(1, 0, 1, '')) && p('users:admin') && e('`A:管理员`');
r($buildTest->assignCreateDataTest(1, 0, 1, '')) && p('productID') && e('1');