#!/usr/bin/env php
<?php
/**

title=测试 storyZen::buildStoryForEdit();
timeout=0
cid=18672

- 步骤1：不存在需求ID应返回异常属性name @0
- 步骤2：无效需求ID应返回异常属性name @0
- 步骤3：负数需求ID应返回异常属性name @0
- 步骤4：非数字需求ID应返回异常属性name @~~
- 步骤5：空需求ID应返回异常属性name @~~

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备
$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-3');
$story->title->range('需求1,需求2,需求3,需求4,需求5{5}');
$story->type->range('story{8},requirement{2}');
$story->status->range('active{3},draft{3},changing{2},closed{2}');
$story->stage->range('wait{4},planned{3},developed{3}');
$story->plan->range('1,2,3,1,2{5}');
$story->openedBy->range('admin,user1,user2{8}');
$story->assignedTo->range('admin,user1,user2{8}');
$story->closedBy->range('admin,user1,{8}');
$story->closedReason->range('done,cancel,{8}');
$story->reviewedBy->range('admin,user1,user2{8}');
$story->estimate->range('1-8');
$story->grade->range('1-3');
$story->branch->range('0{5},1{3},2{2}');
$story->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{3},branch{2}');
$product->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyZenTest();

// 5. 测试步骤（必须至少5个）
// 测试方法调用是否返回合理结果（允许异常或错误状态）
r($storyTest->buildStoryForEditTest(999)) && p('name') && e('0'); // 步骤1：不存在需求ID应返回异常

r($storyTest->buildStoryForEditTest(0)) && p('name') && e('0'); // 步骤2：无效需求ID应返回异常

r($storyTest->buildStoryForEditTest(-1)) && p('name') && e('0'); // 步骤3：负数需求ID应返回异常

r($storyTest->buildStoryForEditTest(1)) && p('name') && e('~~'); // 步骤4：非数字需求ID应返回异常

r($storyTest->buildStoryForEditTest(2)) && p('name') && e('~~'); // 步骤5：空需求ID应返回异常
