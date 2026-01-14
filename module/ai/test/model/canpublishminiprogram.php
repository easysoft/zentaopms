#!/usr/bin/env php
<?php

/**

title=测试 aiModel::canPublishMiniProgram();
timeout=0
cid=14999

- 步骤1：完整有效对象 @1
- 步骤2：缺少name字段 @0
- 步骤3：缺少desc字段 @0
- 步骤4：缺少model字段 @0
- 步骤5：缺少prompt字段 @0
- 步骤6：空字符串字段 @0
- 步骤7：所有字段为空 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 4. 创建测试数据对象
// 测试步骤1：完整有效的小程序对象
$validProgram = new stdClass();
$validProgram->id = 1;
$validProgram->name = 'Test Program';
$validProgram->desc = 'This is a test description';
$validProgram->category = 'work';
$validProgram->model = 1;
$validProgram->prompt = 'This is a test prompt';

// 测试步骤2：name字段为null的小程序对象
$programWithoutName = new stdClass();
$programWithoutName->id = 2;
$programWithoutName->name = null;
$programWithoutName->desc = 'This is a test description';
$programWithoutName->category = 'work';
$programWithoutName->model = 1;
$programWithoutName->prompt = 'This is a test prompt';

// 测试步骤3：desc字段为null的小程序对象
$programWithoutDesc = new stdClass();
$programWithoutDesc->id = 3;
$programWithoutDesc->name = 'Test Program';
$programWithoutDesc->desc = null;
$programWithoutDesc->category = 'work';
$programWithoutDesc->model = 1;
$programWithoutDesc->prompt = 'This is a test prompt';

// 测试步骤4：model字段为null的小程序对象
$programWithoutModel = new stdClass();
$programWithoutModel->id = 4;
$programWithoutModel->name = 'Test Program';
$programWithoutModel->desc = 'This is a test description';
$programWithoutModel->category = 'work';
$programWithoutModel->model = null;
$programWithoutModel->prompt = 'This is a test prompt';

// 测试步骤5：prompt字段为null的小程序对象
$programWithoutPrompt = new stdClass();
$programWithoutPrompt->id = 5;
$programWithoutPrompt->name = 'Test Program';
$programWithoutPrompt->desc = 'This is a test description';
$programWithoutPrompt->category = 'work';
$programWithoutPrompt->model = 1;
$programWithoutPrompt->prompt = null;

// 测试步骤6：name字段为空字符串的小程序对象
$programWithEmptyName = new stdClass();
$programWithEmptyName->id = 6;
$programWithEmptyName->name = '';
$programWithEmptyName->desc = 'This is a test description';
$programWithEmptyName->category = 'work';
$programWithEmptyName->model = 1;
$programWithEmptyName->prompt = 'This is a test prompt';

// 测试步骤7：所有字段都为空的小程序对象
$emptyProgram = new stdClass();
$emptyProgram->id = '';
$emptyProgram->name = '';
$emptyProgram->desc = '';
$emptyProgram->category = '';
$emptyProgram->model = '';
$emptyProgram->prompt = '';

// 5. 强制要求：必须包含至少5个测试步骤
r($aiTest->canPublishMiniProgramTest($validProgram)) && p() && e('1'); // 步骤1：完整有效对象
r($aiTest->canPublishMiniProgramTest($programWithoutName)) && p() && e('0'); // 步骤2：缺少name字段
r($aiTest->canPublishMiniProgramTest($programWithoutDesc)) && p() && e('0'); // 步骤3：缺少desc字段
r($aiTest->canPublishMiniProgramTest($programWithoutModel)) && p() && e('0'); // 步骤4：缺少model字段
r($aiTest->canPublishMiniProgramTest($programWithoutPrompt)) && p() && e('0'); // 步骤5：缺少prompt字段
r($aiTest->canPublishMiniProgramTest($programWithEmptyName)) && p() && e('0'); // 步骤6：空字符串字段
r($aiTest->canPublishMiniProgramTest($emptyProgram)) && p() && e('0'); // 步骤7：所有字段为空