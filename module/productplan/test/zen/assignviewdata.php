#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::assignViewData();
timeout=0
cid=17658

- 执行productplanTest模块的assignViewDataTest方法，参数是$normalPlan 
 - 属性plan @1
 - 属性gradeGroupSet @set
 - 属性actionsSet @0
 - 属性usersSet @set
 - 属性plansSet @set
 - 属性modulesSet @set
- 执行$result
 - 第plan,parentPlan条的id属性 @3
- 执行$result
 - 属性plan @2
 - 属性childrenPlans @3
- 执行$allPropertiesSet @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplan.unittest.class.php';

// zendata数据准备
$productTable = zenData('product');
$productTable->name->range('产品1,产品2,产品3');
$productTable->type->range('normal');
$productTable->status->range('normal');
$productTable->gen(3);

$planTable = zenData('productplan');
$planTable->product->range('1-3');
$planTable->branch->range('0');
$planTable->parent->range('0,-1,1,1,1');
$planTable->title->range('普通计划,父计划,子计划1,子计划2,子计划3');
$planTable->status->range('wait,doing,done,closed,wait');
$planTable->begin->range('2024-01-01,2024-02-01,2024-02-05,2024-02-10,2024-02-15');
$planTable->end->range('2024-12-31,2024-12-31,2024-03-05,2024-03-10,2024-03-15');
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

$productplanTest = new productPlan();

// 测试步骤1：测试普通计划（无父子关系）的视图数据分配
$normalPlan = $tester->loadModel('productplan')->getByID(1);
if(!$normalPlan) $normalPlan = (object)array('id' => 1, 'product' => 1, 'branch' => 0, 'parent' => 0, 'title' => 'test plan 1', 'status' => 'wait');
r($productplanTest->assignViewDataTest($normalPlan)) && p('plan,gradeGroupSet,actionsSet,usersSet,plansSet,modulesSet') && e('1,set,0,set,set,set');

// 测试步骤2：测试子计划（parent>0）的视图数据分配，检查父计划是否设置
$childPlan = $tester->loadModel('productplan')->getByID(3);
if(!$childPlan) $childPlan = (object)array('id' => 3, 'product' => 1, 'branch' => 0, 'parent' => 2, 'title' => 'test plan 3', 'status' => 'doing');
$result = $productplanTest->assignViewDataTest($childPlan);
r($result) && p('plan,parentPlan:id') && e('3,2');

// 测试步骤3：测试父计划（parent=-1）的视图数据分配，检查子计划是否设置
$parentPlan = $tester->loadModel('productplan')->getByID(2);
if(!$parentPlan) $parentPlan = (object)array('id' => 2, 'product' => 1, 'branch' => 0, 'parent' => -1, 'title' => 'test plan 2', 'status' => 'doing');
$result = $productplanTest->assignViewDataTest($parentPlan);
r($result) && p('plan,childrenPlans') && e('2,3');

// 测试步骤4：测试所有必需的视图属性是否正确设置
$completePlan = $tester->loadModel('productplan')->getByID(5);
if(!$completePlan) $completePlan = (object)array('id' => 5, 'product' => 3, 'branch' => 0, 'parent' => 2, 'title' => 'test plan 5', 'status' => 'wait');
$result = $productplanTest->assignViewDataTest($completePlan);
$allPropertiesSet = (
    $result['plan'] == 5 &&
    $result['gradeGroupSet'] == 'set' &&
    $result['actionsSet'] > 0 &&
    $result['usersSet'] == 'set' &&
    $result['plansSet'] == 'set' &&
    $result['modulesSet'] == 'set'
);
r($allPropertiesSet) && p() && e('1');