#!/usr/bin/env php
<?php

/**

title=测试 aiModel::togglePromptStatus();
timeout=0
cid=15071

- 步骤1：使用ID切换draft状态prompt为active @1
- 步骤2：使用ID切换active状态prompt为draft @1
- 步骤3：使用对象切换prompt状态 @1
- 步骤4：指定具体状态进行切换 @1
- 步骤5：测试不存在的prompt ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_agent');
$table->id->range('1-10');
$table->name->range('状态切换测试1,状态切换测试2,状态切换测试3,状态切换测试4,状态切换测试5,状态切换测试6,状态切换测试7,状态切换测试8,状态切换测试9,状态切换测试10');
$table->desc->range('测试状态切换功能的提示词描述{10}');
$table->model->range('1-3');
$table->module->range('story{4},task{3},bug{3}');
$table->source->range('story.title,task.name,bug.title');
$table->targetForm->range('story.edit,task.edit,bug.edit');
$table->purpose->range('测试状态切换目的{10}');
$table->elaboration->range('测试状态切换详细说明{10}');
$table->role->range('测试角色描述{10}');
$table->characterization->range('测试角色特征{10}');
$table->status->range('draft{5},active{5}');
$table->createdBy->range('admin,user1,user2');
$table->createdDate->range('`2023-08-10 10:00:00`,`2023-08-11 11:00:00`,`2023-08-12 12:00:00`');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->togglePromptStatusTest(1, '')) && p() && e('1'); // 步骤1：使用ID切换draft状态prompt为active
r($aiTest->togglePromptStatusTest(6, '')) && p() && e('1'); // 步骤2：使用ID切换active状态prompt为draft
r($aiTest->togglePromptStatusTest($aiTest->getPromptByIdTest(2), '')) && p() && e('1'); // 步骤3：使用对象切换prompt状态
r($aiTest->togglePromptStatusTest(3, 'active')) && p() && e('1'); // 步骤4：指定具体状态进行切换
r($aiTest->togglePromptStatusTest(999, '')) && p() && e('0'); // 步骤5：测试不存在的prompt ID