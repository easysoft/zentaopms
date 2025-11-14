#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignModulesForCreate();
timeout=0
cid=19074

- 步骤1：有moduleID和storyID的正常情况属性currentModuleID @5
- 步骤2：无moduleID但有storyID，应使用story的module属性currentModuleID @2
- 步骤3：无storyID且无moduleID，产品ID不匹配cookie属性currentModuleID @0
- 步骤4：无storyID且无moduleID，产品ID匹配cookie属性currentModuleID @2
- 步骤5：branch为all时的特殊处理情况属性currentModuleID @3
- 步骤6：storyID存在但story不存在的边界情况属性currentModuleID @2
- 步骤7：branches数组为空时的处理属性currentModuleID @8

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-3');
$story->module->range('5-15');
$story->title->range('Story{1-10}');
$story->status->range('active');
$story->deleted->range('0');
$story->gen(10);

$module = zenData('module');
$module->id->range('1-30');
$module->root->range('1-5');
$module->name->range('Module{1-30}');
$module->type->range('case');
$module->deleted->range('0');
$module->gen(30);

$scene = zenData('scene');
$scene->id->range('1-20');
$scene->product->range('1-3');
$scene->module->range('1-30');
$scene->title->range('Scene{1-20}');
$scene->deleted->range('0');
$scene->gen(20);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('Product{1-5}');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignModulesForCreateTest(1, 5, '0', 1, array('0' => '主干', '1' => '分支1'))) && p('currentModuleID') && e('5'); // 步骤1：有moduleID和storyID的正常情况
r($testcaseTest->assignModulesForCreateTest(1, 0, '0', 1, array('0' => '主干', '1' => '分支1'))) && p('currentModuleID') && e('2'); // 步骤2：无moduleID但有storyID，应使用story的module
r($testcaseTest->assignModulesForCreateTest(2, 0, '0', 0, array('0' => '主干', '1' => '分支1'))) && p('currentModuleID') && e('0'); // 步骤3：无storyID且无moduleID，产品ID不匹配cookie
r($testcaseTest->assignModulesForCreateTest(1, 0, '0', 0, array('0' => '主干', '1' => '分支1'))) && p('currentModuleID') && e('2'); // 步骤4：无storyID且无moduleID，产品ID匹配cookie
r($testcaseTest->assignModulesForCreateTest(1, 3, 'all', 0, array('0' => '主干', '1' => '分支1'))) && p('currentModuleID') && e('3'); // 步骤5：branch为all时的特殊处理情况
r($testcaseTest->assignModulesForCreateTest(1, 0, '0', 999, array('0' => '主干', '1' => '分支1'))) && p('currentModuleID') && e('2'); // 步骤6：storyID存在但story不存在的边界情况
r($testcaseTest->assignModulesForCreateTest(1, 8, '0', 0, array())) && p('currentModuleID') && e('8'); // 步骤7：branches数组为空时的处理