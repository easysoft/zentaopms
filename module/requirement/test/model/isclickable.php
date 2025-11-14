#!/usr/bin/env php
<?php

/**

title=测试 requirementModel::isClickable();
timeout=0
cid=18193

- 步骤1：正常需求状态，close动作 @1
- 步骤2：已关闭需求状态，close动作 @0
- 步骤3：正常需求状态，activate动作 @0
- 步骤4：已关闭需求状态，activate动作 @1
- 步骤5：正常需求状态，assignto动作 @1
- 步骤6：已关闭需求状态，assignto动作 @0
- 步骤7：审核中状态，recall动作 @1
- 步骤8：正常状态，recall动作 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/requirement.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->type->range('requirement{10}');
$table->status->range('active{5},closed{3},reviewing{2}');
$table->title->range('需求标题1,需求标题2,需求标题3,需求标题4,需求标题5,需求标题6,需求标题7,需求标题8,需求标题9,需求标题10');
$table->isParent->range('0{10}');
$table->parent->range('0{10}');
$table->product->range('1{10}');
$table->branch->range('0{10}');
$table->module->range('0{10}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$requirementTest = new requirementTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$activeStory = new stdClass();
$activeStory->id = 1;
$activeStory->status = 'active';
$activeStory->type = 'requirement';
$activeStory->isParent = '0';

$closedStory = new stdClass();
$closedStory->id = 2;
$closedStory->status = 'closed';
$closedStory->type = 'requirement';
$closedStory->isParent = '0';

$reviewingStory = new stdClass();
$reviewingStory->id = 3;
$reviewingStory->status = 'reviewing';
$reviewingStory->type = 'requirement';
$reviewingStory->isParent = '0';

r($requirementTest->isClickableTest($activeStory, 'close')) && p() && e('1'); // 步骤1：正常需求状态，close动作
r($requirementTest->isClickableTest($closedStory, 'close')) && p() && e('0'); // 步骤2：已关闭需求状态，close动作
r($requirementTest->isClickableTest($activeStory, 'activate')) && p() && e('0'); // 步骤3：正常需求状态，activate动作
r($requirementTest->isClickableTest($closedStory, 'activate')) && p() && e('1'); // 步骤4：已关闭需求状态，activate动作
r($requirementTest->isClickableTest($activeStory, 'assignto')) && p() && e('1'); // 步骤5：正常需求状态，assignto动作
r($requirementTest->isClickableTest($closedStory, 'assignto')) && p() && e('0'); // 步骤6：已关闭需求状态，assignto动作
r($requirementTest->isClickableTest($reviewingStory, 'recall')) && p() && e('1'); // 步骤7：审核中状态，recall动作
r($requirementTest->isClickableTest($activeStory, 'recall')) && p() && e('0'); // 步骤8：正常状态，recall动作