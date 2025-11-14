#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getRequirements();
timeout=0
cid=18552

- 步骤1：获取产品1的活跃需求列表
 - 属性1 @需求1
 - 属性2 @需求2
 - 属性3 @需求3
- 步骤2：获取不存在产品的需求列表返回空 @0
- 步骤3：验证产品1的活跃需求数量 @3
- 步骤4：获取有已删除需求的产品 @0
- 步骤5：测试无效产品ID（0） @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 准备测试数据
$storyTable = zenData('story');
$storyTable->id->range('1-20');
$storyTable->product->range('1{3},2{3},3{3},999{2},4{3},5{6}');
$storyTable->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10,需求11,需求12,需求13,需求14,需求15,需求16,需求17,需求18,需求19,需求20');
$storyTable->type->range('requirement{15},story{5}');
$storyTable->status->range('active{3},reviewing{3},active{3},draft{3},closed{3},active{5}');
$storyTable->deleted->range('0{18},1{2}');
$storyTable->gen(20);

// 用户登录
su('admin');

// 创建测试实例
$storyTest = new storyTest();

r($storyTest->getRequirementsTest(1)) && p('1,2,3') && e('需求1,需求2,需求3'); // 步骤1：获取产品1的活跃需求列表
r($storyTest->getRequirementsTest(100)) && p() && e('0'); // 步骤2：获取不存在产品的需求列表返回空
r(count($storyTest->getRequirementsTest(1))) && p() && e('3'); // 步骤3：验证产品1的活跃需求数量
r($storyTest->getRequirementsTest(999)) && p() && e('0'); // 步骤4：获取有已删除需求的产品
r($storyTest->getRequirementsTest(0)) && p() && e('0'); // 步骤5：测试无效产品ID（0）