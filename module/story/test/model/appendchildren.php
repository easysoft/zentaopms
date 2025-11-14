#!/usr/bin/env php
<?php

/**

title=测试 storyModel::appendChildren();
timeout=0
cid=18462

- 步骤1：为epic需求追加子需求，验证包含原需求第0条的id属性 @1
- 步骤2：空需求数组情况，返回空数组 @0
- 步骤3：不存在的产品ID情况，返回原需求数组第0条的id属性 @1
- 步骤4：requirement类型需求无子需求时返回原需求第0条的id属性 @2
- 步骤5：不同产品下的需求测试第0条的id属性 @11

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-20');
$story->root->range('1{5},2{5},3{5},4{5}');
$story->parent->range('0{4},1,0{4},2,0{4},3,0{4},4');
$story->product->range('1{10},2{10}');
$story->type->range('epic{4},requirement{6},story{10}');
$story->grade->range('1{4},2{6},3{10}');
$story->title->range('Epic需求,Requirement需求,Story需求');
$story->status->range('active');
$story->deleted->range('0');
$story->vision->range('rnd');
$story->gen(20);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->type->range('normal');
$product->deleted->range('0');
$product->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->appendChildrenTest(1, array((object)array('id' => 1, 'root' => 1)), 'epic')) && p('0:id') && e('1'); // 步骤1：为epic需求追加子需求，验证包含原需求
r($storyTest->appendChildrenTest(1, array(), 'story')) && p() && e('0'); // 步骤2：空需求数组情况，返回空数组  
r($storyTest->appendChildrenTest(999, array((object)array('id' => 1, 'root' => 1)), 'story')) && p('0:id') && e('1'); // 步骤3：不存在的产品ID情况，返回原需求数组
r($storyTest->appendChildrenTest(1, array((object)array('id' => 2, 'root' => 2)), 'requirement')) && p('0:id') && e('2'); // 步骤4：requirement类型需求无子需求时返回原需求
r($storyTest->appendChildrenTest(2, array((object)array('id' => 11, 'root' => 3)), 'story')) && p('0:id') && e('11'); // 步骤5：不同产品下的需求测试