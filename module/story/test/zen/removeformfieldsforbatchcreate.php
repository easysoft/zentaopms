#!/usr/bin/env php
<?php

/**

title=测试 storyZen::removeFormFieldsForBatchCreate();
timeout=0
cid=0

- 步骤1：正常情况，plan字段保留属性plan @test
- 步骤2：隐藏计划字段，plan被移除属性plan @0
- 步骤3：非看板执行类型，region被移除属性region @~~
- 步骤4：看板执行类型，region保留属性region @test
- 步骤5：项目标签页，parent隐藏第parent条的hidden属性 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. 准备测试数据
$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->type->range('project{5}');
$projectTable->hasProduct->range('1{3},0{2}');
$projectTable->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyTest();

// 5. 测试步骤（必须包含至少5个）
r($storyTest->removeFormFieldsForBatchCreateTest(array('plan' => 'test', 'region' => 'test', 'lane' => 'test', 'parent' => 'test'), false, 'story', 0)) && p('plan') && e('test'); // 步骤1：正常情况，plan字段保留
r($storyTest->removeFormFieldsForBatchCreateTest(array('plan' => 'test', 'region' => 'test', 'lane' => 'test'), true, 'story', 0)) && p('plan') && e('0'); // 步骤2：隐藏计划字段，plan被移除
r($storyTest->removeFormFieldsForBatchCreateTest(array('plan' => 'test', 'region' => 'test', 'lane' => 'test'), false, 'story', 0)) && p('region') && e('~~'); // 步骤3：非看板执行类型，region被移除
r($storyTest->removeFormFieldsForBatchCreateTest(array('plan' => 'test', 'region' => 'test', 'lane' => 'test'), false, 'kanban', 0)) && p('region') && e('test'); // 步骤4：看板执行类型，region保留
r($storyTest->removeFormFieldsForBatchCreateTest(array('plan' => 'test', 'parent' => array('hidden' => false)), false, 'story', 1, 'project')) && p('parent:hidden') && e('1'); // 步骤5：项目标签页，parent隐藏