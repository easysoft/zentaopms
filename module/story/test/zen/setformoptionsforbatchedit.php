#!/usr/bin/env php
<?php

/**

title=测试 storyZen::setFormOptionsForBatchEdit();
timeout=0
cid=18704

- 单产品批量编辑时branchProduct为0 @0
- 单产品批量编辑时检查users属性admin @`A:管理员`
- 单产品批量编辑时检查moduleList索引0 @/
- 多产品批量编辑时branchProduct为1 @1
- 多产品批量编辑时检查users属性为空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->code->range('product1,product2,product3,product4,product5');
$product->type->range('normal{3},branch{2}');
$product->status->range('normal');
$product->gen(5);

$module = zenData('module');
$module->id->range('1-20');
$module->root->range('1{5},2{5},3{5},4{3},5{2}');
$module->branch->range('0{15},1{3},2{2}');
$module->name->range('模块1,模块2,模块3,模块4,模块5{15}');
$module->type->range('story');
$module->gen(20);

$productplan = zenData('productplan');
$productplan->id->range('1-10');
$productplan->product->range('1-5');
$productplan->branch->range('0{8},1,2');
$productplan->title->range('计划1,计划2,计划3,计划4,计划5{5}');
$productplan->gen(10);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->deleted->range('0');
$user->gen(5);

su('admin');

$storyTest = new storyZenTest();

$story1 = new stdClass();
$story1->id = 1;
$story1->product = 1;
$story1->branch = 0;
$story1->module = 1;
$story1->plan = '';
$story1->status = 'active';
$story1->type = 'story';

$story2 = new stdClass();
$story2->id = 2;
$story2->product = 1;
$story2->branch = 0;
$story2->module = 2;
$story2->plan = '';
$story2->status = 'active';
$story2->type = 'story';

$story3 = new stdClass();
$story3->id = 3;
$story3->product = 2;
$story3->branch = 0;
$story3->module = 6;
$story3->plan = '';
$story3->status = 'active';
$story3->type = 'story';

$story4 = new stdClass();
$story4->id = 4;
$story4->product = 3;
$story4->branch = 0;
$story4->module = 11;
$story4->plan = '';
$story4->status = 'closed';
$story4->type = 'story';

$singleProductStories = array(1 => $story1, 2 => $story2);
$multiProductStories = array(1 => $story1, 3 => $story3, 4 => $story4);

r($storyTest->setFormOptionsForBatchEditTest(1, 0, $singleProductStories)) && p('branchProduct') && e('0');
r($storyTest->setFormOptionsForBatchEditTest(1, 0, $singleProductStories)) && p('users:admin') && e('`A:管理员`');
r($storyTest->setFormOptionsForBatchEditTest(1, 0, $singleProductStories)) && p('moduleList:0') && e('/');
r($storyTest->setFormOptionsForBatchEditTest(0, 0, $multiProductStories)) && p('branchProduct') && e('1');
r($storyTest->setFormOptionsForBatchEditTest(0, 0, $multiProductStories)) && p('plans') && e('0');
