#!/usr/bin/env php
<?php

/**

title=测试 aiModel::createPrompt();
timeout=0
cid=15013

- 步骤1：正常情况创建成功 @2
- 步骤2：空name字段返回错误第name条的0属性 @『name』不能为空。
- 步骤3：重复名称返回错误第name条的0属性 @该名称已使用，请尝试其他名称。
- 步骤4：完整字段创建成功 @3
- 步骤5：验证操作记录创建成功 @4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$ai_prompt = zenData('ai_prompt');
$ai_prompt->id->range('1');
$ai_prompt->name->range('existing_prompt');
$ai_prompt->desc->range('这是一个已存在的提示词');
$ai_prompt->module->range('story');
$ai_prompt->status->range('active');
$ai_prompt->createdBy->range('admin');
$ai_prompt->createdDate->range('`2023-01-01 10:00:00`');
$ai_prompt->gen(1);

$action = zenData('action');
$action->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->createPromptTest((object)array('name' => 'test_new_prompt', 'desc' => '新测试提示词'))) && p() && e('2'); // 步骤1：正常情况创建成功
r($aiTest->createPromptTest((object)array('name' => '', 'desc' => '空name字段'))) && p('name:0') && e('『name』不能为空。'); // 步骤2：空name字段返回错误
r($aiTest->createPromptTest((object)array('name' => 'existing_prompt', 'desc' => '重复名称'))) && p('name:0') && e('该名称已使用，请尝试其他名称。'); // 步骤3：重复名称返回错误
r($aiTest->createPromptTest((object)array('name' => 'complete_prompt', 'desc' => '完整提示词', 'module' => 'story', 'purpose' => '测试目的', 'role' => '测试角色'))) && p() && e('3'); // 步骤4：完整字段创建成功
r($aiTest->createPromptTest((object)array('name' => 'action_test_prompt', 'desc' => '操作记录测试'))) && p() && e('4'); // 步骤5：验证操作记录创建成功