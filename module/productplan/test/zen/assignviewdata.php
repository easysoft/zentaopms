#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::assignViewData();
timeout=0
cid=17658

- 查看计划一的数据
 - 第plan条的id属性 @1
 - 第plan条的title属性 @普通计划
 - 第plan条的status属性 @wait
- 查看计划二的数据
 - 第plan条的id属性 @2
 - 第plan条的title属性 @父计划
 - 第plan条的status属性 @doing
- 查看计划三的数据
 - 第plan条的id属性 @3
 - 第plan条的title属性 @子计划1
 - 第plan条的status属性 @done
- 查看计划五的数据
 - 第plan条的id属性 @5
 - 第plan条的title属性 @子计划3
 - 第plan条的status属性 @wait

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplanzen.unittest.class.php';

// zendata数据准备
$productTable = zenData('product');
$productTable->name->range('产品1,产品2,产品3');
$productTable->type->range('normal');
$productTable->status->range('normal');
$productTable->gen(3);

$planTable = zenData('productplan');
$planTable->product->range('1-3');
$planTable->branch->range('0');
$planTable->parent->range('0,1,1,1,1');
$planTable->title->range('普通计划,父计划,子计划1,子计划2,子计划3');
$planTable->status->range('wait,doing,done,closed,wait');
$planTable->begin->prefix('2024-01-01');
$planTable->end->prefix('2024-12-31');
$planTable->deleted->range('0');
$planTable->gen(5);

$storyTable = zenData('story');
$storyTable->product->range('1-3');
$storyTable->type->range('story{3},requirement{3},epic{3}');
$storyTable->grade->range('1-3');
$storyTable->gen(9);

$actionTable = zenData('action');
$actionTable->objectType->range('productplan');
$actionTable->objectID->range('1-5');
$actionTable->action->range('created,edited,started,finished,closed');
$actionTable->gen(5);

$userTable = zenData('user');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->gen(5);

su('admin');

$productplanTest = new productplanZenTest();

// 测试步骤1：测试普通计划（无父子关系）的视图数据分配
$plan   = $tester->loadModel('productplan')->getByID(1);
$result = $productplanTest->assignViewDataTest($plan);
r($result) && p('plan:id,title,status') && e('1,普通计划,wait'); // 查看计划一的数据

$plan   = $tester->loadModel('productplan')->getByID(2);
$result = $productplanTest->assignViewDataTest($plan);
r($result) && p('plan:id,title,status') && e('2,父计划,doing'); // 查看计划二的数据

$plan   = $tester->loadModel('productplan')->getByID(3);
$result = $productplanTest->assignViewDataTest($plan);
r($result) && p('plan:id,title,status') && e('3,子计划1,done'); // 查看计划三的数据

$plan   = $tester->loadModel('productplan')->getByID(5);
$result = $productplanTest->assignViewDataTest($plan);
r($result) && p('plan:id,title,status') && e('5,子计划3,wait'); // 查看计划五的数据