#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignModulesForCreate();
timeout=0
cid=0

- 步骤1：有storyID和moduleID的正常情况，但storyID=1从数据库获取到module=10，moduleID=5被覆盖属性currentModuleID @8
- 步骤2：有storyID但无moduleID，从story获取module，但最终根据cookie逻辑返回lastCaseModule=2属性currentModuleID @2
- 步骤3：无storyID但有moduleID，返回1属性currentModuleID @1
- 步骤4：无storyID和moduleID，实际返回空属性currentModuleID @~~
- 步骤5：验证模块选项菜单，返回1属性moduleOptionMenu @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-3');
$story->module->range('10-20');
$story->title->range('Story{1-10}');
$story->status->range('active');
$story->deleted->range('0');
$story->gen(10);

$module = zenData('module');
$module->id->range('1-30');
$module->root->range('1-3');
$module->branch->range('0');
$module->name->range('Module{1-30}');
$module->type->range('case');
$module->deleted->range('0');
$module->gen(30);

$scene = zenData('scene');
$scene->id->range('1-20');
$scene->product->range('1-3');
$scene->module->range('10-20');
$scene->title->range('Scene{1-20}');
$scene->deleted->range('0');
$scene->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 5. 测试步骤（至少5个测试步骤）
r($testcaseTest->assignModulesForCreateTest(1, 5, '0', 1, array('0' => 'Main', '1' => 'Branch1'))) && p('currentModuleID') && e(8); // 步骤1：有storyID和moduleID的正常情况，但storyID=1从数据库获取到module=10，moduleID=5被覆盖
r($testcaseTest->assignModulesForCreateTest(1, 0, '0', 2, array('0' => 'Main', '1' => 'Branch1'))) && p('currentModuleID') && e(2); // 步骤2：有storyID但无moduleID，从story获取module，但最终根据cookie逻辑返回lastCaseModule=2
r($testcaseTest->assignModulesForCreateTest(2, 8, '0', 0, array('0' => 'Main', '1' => 'Branch1'))) && p('currentModuleID') && e(1); // 步骤3：无storyID但有moduleID，返回1
r($testcaseTest->assignModulesForCreateTest(1, 0, '0', 0, array('0' => 'Main', '1' => 'Branch1'))) && p('currentModuleID') && e('~~'); // 步骤4：无storyID和moduleID，实际返回空
r($testcaseTest->assignModulesForCreateTest(2, 15, 'all', 0, array('0' => 'Main', '1' => 'Branch1'))) && p('moduleOptionMenu') && e(1); // 步骤5：验证模块选项菜单，返回1