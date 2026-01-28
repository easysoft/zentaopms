#!/usr/bin/env php
<?php

/**

title=测试 executionModel::computeBurn();
timeout=0
cid=16286

- 执行executionTester模块的computeBurnTest方法，参数是'' 第3条的executionName属性 @code3
- 执行executionTester模块的computeBurnTest方法，参数是3 第3条的executionName属性 @code3
- 执行executionTester模块的computeBurnTest方法，参数是array 第3条的executionName属性 @code3
- 执行executionTester模块的computeBurnTest方法，参数是999  @0
- 执行executionTester模块的computeBurnTest方法，参数是9  @0
- 执行executionTester模块的computeBurnTest方法，参数是'3' 第3条的executionName属性 @code3
- 执行executionTester模块的computeBurnTest方法，参数是array 第3条的executionName属性 @code3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备用户数据
zenData('user')->gen(5);
su('admin');

// 准备执行数据
$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1,迭代2,阶段2,项目2,迭代3,阶段3');
$execution->type->range('program,project,sprint,stage,kanban,sprint,stage,project,sprint,stage');
$execution->code->range('1-10')->prefix('code');
$execution->parent->range('0,1,2{3},6,7,8{2}');
$execution->status->range('wait{6},doing{2},closed{1},suspended{1}');
$execution->lifetime->range('product{10}');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

// 准备任务数据，覆盖不同状态
$task = zenData('task');
$task->id->range('1-30');
$task->execution->range('3{10},4{10},6{10}');
$task->status->range('wait{8},doing{7},done{5},closed{5},cancel{5}');
$task->estimate->range('1-10:0.5');
$task->left->range('0.5-5:0.5');
$task->consumed->range('0.5-8:0.5');
$task->deleted->range('0{25},1{5}');
$task->isParent->range('0{25},1{5}');
$task->gen(30);

// 准备需求-项目关联数据
$projectStory = zenData('projectstory');
$projectStory->project->range('3{5},4{5},6{5}');
$projectStory->story->range('1-15');
$projectStory->gen(15);

// 准备需求数据
$story = zenData('story');
$story->id->range('1-15');
$story->estimate->range('1-5:0.5');
$story->status->range('active{10},closed{5}');
$story->stage->range('wait{3},planned{3},projected{2},developing{2},closed{5}');
$story->deleted->range('0{12},1{3}');
$story->isParent->range('0{12},1{3}');
$story->gen(15);

// 准备产品数据
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->gen(5);

// 创建测试实例
$executionTester = new executionModelTest();

// 测试步骤1：空参数计算所有符合条件的执行燃尽图
r($executionTester->computeBurnTest('')) && p('3:executionName') && e('code3');

// 测试步骤2：单个执行ID计算燃尽图（sprint类型）
r($executionTester->computeBurnTest(3)) && p('3:executionName') && e('code3');

// 测试步骤3：多个执行ID数组计算燃尽图
r($executionTester->computeBurnTest(array(3, 4))) && p('3:executionName') && e('code3');

// 测试步骤4：不存在的执行ID计算燃尽图
r($executionTester->computeBurnTest(999)) && p() && e('0');

// 测试步骤5：已关闭状态的执行ID计算燃尽图
r($executionTester->computeBurnTest(9)) && p() && e('0');

// 测试步骤6：字符串形式的执行ID计算燃尽图
r($executionTester->computeBurnTest('3')) && p('3:executionName') && e('code3');

// 测试步骤7：包含无效ID的混合数组计算燃尽图
r($executionTester->computeBurnTest(array(3, 999, 4))) && p('3:executionName') && e('code3');