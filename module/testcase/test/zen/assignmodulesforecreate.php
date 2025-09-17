#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignModulesForCreate();
timeout=0
cid=0

- 步骤1：正常情况，有storyID和moduleID属性currentModuleID @5
- 步骤2：有storyID但无moduleID属性currentModuleID @12
- 步骤3：无storyID但有moduleID属性currentModuleID @8
- 步骤4：无storyID和moduleID，但productID匹配cookie属性currentModuleID @2
- 步骤5：无storyID和moduleID，productID不匹配cookie属性currentModuleID @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备
$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-3');
$story->module->range('10-20');
$story->title->range('Story{1-10}');
$story->gen(10);

$module = zenData('module');
$module->id->range('1-30');
$module->root->range('1-3');
$module->branch->range('0');
$module->name->range('Module{1-30}');
$module->type->range('case');
$module->gen(30);

$scene = zenData('scene');
$scene->id->range('1-20');
$scene->product->range('1-3');
$scene->module->range('10-20');
$scene->title->range('Scene{1-20}');
$scene->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseTest();

// 5. 测试步骤
r($testcaseTest->assignModulesForCreateTest(1, 5, '0', 1, array('0' => 'Main', '1' => 'Branch1'))) && p('currentModuleID') && e(5); // 步骤1：正常情况，有storyID和moduleID
r($testcaseTest->assignModulesForCreateTest(1, 0, '0', 2, array('0' => 'Main', '1' => 'Branch1'))) && p('currentModuleID') && e(12); // 步骤2：有storyID但无moduleID
r($testcaseTest->assignModulesForCreateTest(2, 8, '0', 0, array('0' => 'Main', '1' => 'Branch1'))) && p('currentModuleID') && e(8); // 步骤3：无storyID但有moduleID
r($testcaseTest->assignModulesForCreateTest(1, 0, '0', 0, array('0' => 'Main', '1' => 'Branch1'))) && p('currentModuleID') && e(2); // 步骤4：无storyID和moduleID，但productID匹配cookie
r($testcaseTest->assignModulesForCreateTest(3, 0, '0', 0, array('0' => 'Main', '1' => 'Branch1'))) && p('currentModuleID') && e(0); // 步骤5：无storyID和moduleID，productID不匹配cookie