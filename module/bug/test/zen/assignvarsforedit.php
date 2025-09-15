#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignVarsForEdit();
timeout=0
cid=0

- 执行bugTest模块的assignVarsForEditTest方法，参数是$bug1, $product1 
 - 属性executedSuccessfully @1
 - 属性hasExecution @1
 - 属性isShadowProduct @0
- 执行bugTest模块的assignVarsForEditTest方法，参数是$bug2, $product1 
 - 属性executedSuccessfully @1
 - 属性hasExecution @1
 - 属性isShadowProduct @0
- 执行bugTest模块的assignVarsForEditTest方法，参数是$bug3, $product1 
 - 属性executedSuccessfully @1
 - 属性hasProject @1
 - 属性isShadowProduct @0
- 执行bugTest模块的assignVarsForEditTest方法，参数是$bug4, $product1 
 - 属性executedSuccessfully @1
 - 属性hasDefault @1
 - 属性isShadowProduct @0
- 执行bugTest模块的assignVarsForEditTest方法，参数是$bug5, $productShadow 
 - 属性executedSuccessfully @1
 - 属性hasDefault @1
 - 属性isShadowProduct @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

$bug = zenData('bug');
$bug->id->range('1-5');
$bug->title->range('Bug{1}, Bug{2}, Bug{3}, Bug{4}, Bug{5}');
$bug->product->range('1-5');
$bug->execution->range('0{2}, 101{2}, 102{1}');
$bug->project->range('0{2}, 11{2}, 12{1}');
$bug->assignedTo->range('admin{2}, user1, user2, user3');
$bug->openedBuild->range('1,2,3,4,5');
$bug->story->range('1-5');
$bug->module->range('1-5');
$bug->gen(5);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('Product{1}, Product{2}, Product{3}, Product{4}, Shadow Product{1}');
$product->type->range('normal{4}, branch{1}');
$product->shadow->range('0{4}, 1{1}');
$product->status->range('normal{5}');
$product->gen(5);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin, user1, user2, user3, user4');
$user->realname->range('Administrator, Test User1, Test User2, Test User3, Test User4');
$user->deleted->range('0{5}');
$user->gen(5);

su('admin');

$bugTest = new bugTest();

$bug1 = new stdclass();
$bug1->id = 1;
$bug1->product = 1;
$bug1->execution = 101;
$bug1->project = 11;
$bug1->branch = 'main';
$bug1->assignedTo = 'admin';
$bug1->openedBuild = '1';
$bug1->story = 1;
$bug1->module = 1;

$product1 = new stdclass();
$product1->id = 1;
$product1->name = 'Test Product';
$product1->shadow = 0;
$product1->type = 'normal';

$bug2 = new stdclass();
$bug2->id = 2;
$bug2->product = 2;
$bug2->execution = 102;
$bug2->project = 0;
$bug2->branch = '';
$bug2->assignedTo = 'user1';
$bug2->openedBuild = '2';
$bug2->story = 2;
$bug2->module = 2;

$bug3 = new stdclass();
$bug3->id = 3;
$bug3->product = 3;
$bug3->execution = 0;
$bug3->project = 12;
$bug3->branch = '';
$bug3->assignedTo = 'user2';
$bug3->openedBuild = '3';
$bug3->story = 3;
$bug3->module = 3;

$bug4 = new stdclass();
$bug4->id = 4;
$bug4->product = 4;
$bug4->execution = 0;
$bug4->project = 0;
$bug4->branch = '';
$bug4->assignedTo = 'user3';
$bug4->openedBuild = '4';
$bug4->story = 4;
$bug4->module = 4;

$productShadow = new stdclass();
$productShadow->id = 5;
$productShadow->name = 'Shadow Product';
$productShadow->shadow = 1;
$productShadow->type = 'normal';

$bug5 = new stdclass();
$bug5->id = 5;
$bug5->product = 5;
$bug5->execution = 0;
$bug5->project = 0;
$bug5->branch = '';
$bug5->assignedTo = 'user4';
$bug5->openedBuild = '5';
$bug5->story = 5;
$bug5->module = 5;

r($bugTest->assignVarsForEditTest($bug1, $product1)) && p('executedSuccessfully,hasExecution,isShadowProduct') && e('1,1,0');
r($bugTest->assignVarsForEditTest($bug2, $product1)) && p('executedSuccessfully,hasExecution,isShadowProduct') && e('1,1,0');
r($bugTest->assignVarsForEditTest($bug3, $product1)) && p('executedSuccessfully,hasProject,isShadowProduct') && e('1,1,0');
r($bugTest->assignVarsForEditTest($bug4, $product1)) && p('executedSuccessfully,hasDefault,isShadowProduct') && e('1,1,0');
r($bugTest->assignVarsForEditTest($bug5, $productShadow)) && p('executedSuccessfully,hasDefault,isShadowProduct') && e('1,1,1');