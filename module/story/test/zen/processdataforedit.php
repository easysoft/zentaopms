#!/usr/bin/env php
<?php

/**

title=测试 storyZen::processDataForEdit();
timeout=0
cid=0

- 执行storyTest模块的processDataForEditTest方法，参数是1, $story1 属性linkStories @
- 执行storyTest模块的processDataForEditTest方法，参数是2, $story2 属性linkRequirements @
- 执行storyTest模块的processDataForEditTest方法，参数是3, $story3 属性status @changing
- 执行storyTest模块的processDataForEditTest方法，参数是4, $story4 
 - 属性plan @1
- 执行storyTest模块的processDataForEditTest方法，参数是4, $story5 属性stagedBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->type->range('story{5}, requirement{5}');
$table->status->range('active{3}, changing{2}, draft{2}, closed{3}');
$table->stage->range('wait{4}, planned{2}, testing{2}, tested{2}');
$table->title->range('测试故事1, 测试需求1, 测试史诗1, 功能需求1, 性能需求1');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 清理全局变量
unset($_POST);

// 步骤1：测试story类型设置linkStories字段
$story1 = new stdClass();
$story1->type = 'story';
$story1->status = 'active';
r($storyTest->processDataForEditTest(1, $story1)) && p('linkStories') && e('');

// 步骤2：测试requirement类型设置linkRequirements字段
$story2 = new stdClass();
$story2->type = 'requirement';
$story2->status = 'active';
r($storyTest->processDataForEditTest(2, $story2)) && p('linkRequirements') && e('');

// 步骤3：测试changing状态保持不变
$story3 = new stdClass();
$story3->type = 'story';
$story3->status = 'draft';
r($storyTest->processDataForEditTest(3, $story3)) && p('status') && e('changing');

// 步骤4：测试计划数组转换
$_POST['plan'] = array('1', '2', '3');
$story4 = new stdClass();
$story4->type = 'story';
$story4->status = 'active';
r($storyTest->processDataForEditTest(4, $story4)) && p('plan') && e('1,2,3');

// 步骤5：测试阶段变更设置stagedBy
unset($_POST['plan']);
$story5 = new stdClass();
$story5->type = 'story';
$story5->status = 'active';
$story5->stage = 'tested';
r($storyTest->processDataForEditTest(4, $story5)) && p('stagedBy') && e('admin');