#!/usr/bin/env php
<?php

/**

title=测试 aiModel::toggleModel();
timeout=0
cid=0

- 步骤1：正常启用模型ID为1的AI模型 @1
- 步骤2：正常禁用模型ID为2的AI模型 @1
- 步骤3：切换不存在的模型ID为999 @1
- 步骤4：使用false值禁用模型ID为3的模型 @1
- 步骤5：使用null值对模型ID为4切换 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$aiModelTable = zenData('ai_model');
$aiModelTable->id->range('1-10');
$aiModelTable->type->range('chat{5}, code{3}, translate{2}');
$aiModelTable->vendor->range('openai{4}, zhipu{3}, baidu{2}, aliyun{1}');
$aiModelTable->credentials->range('test-credentials{10}');
$aiModelTable->name->range('GPT-4{2}, GPT-3.5{2}, ChatGLM{2}, ERNIE{2}, Qwen{2}');
$aiModelTable->desc->range('模型描述信息{10}');
$aiModelTable->createdBy->range('admin');
$aiModelTable->createdDate->range('`2024-08-01 10:00:00`');
$aiModelTable->enabled->range('0{3}, 1{7}');
$aiModelTable->deleted->range('0');
$aiModelTable->gen(10);

$imChatTable = zenData('im_chat');
$imChatTable->id->range('1-15');
$imChatTable->gid->range('&ai-1{3}, &ai-2{3}, &ai-3{2}, &ai-5{2}, &normal-group{5}');
$imChatTable->name->range('AI助手聊天{8}, 普通群聊{5}, 项目讨论{2}');
$imChatTable->type->range('group{10}, one2one{5}');
$imChatTable->admins->range('admin');
$imChatTable->subject->range('0');
$imChatTable->public->range('0{8}, 1{7}');
$imChatTable->createdBy->range('admin');
$imChatTable->createdDate->range('`2024-07-01 09:00:00`');
$imChatTable->adminInvite->range('0');
$imChatTable->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->toggleModelTest(1, true)) && p() && e('1');       // 步骤1：正常启用模型ID为1的AI模型
r($aiTest->toggleModelTest(2, false)) && p() && e('1');      // 步骤2：正常禁用模型ID为2的AI模型
r($aiTest->toggleModelTest(999, true)) && p() && e('1');     // 步骤3：切换不存在的模型ID为999
r($aiTest->toggleModelTest(3, false)) && p() && e('1');      // 步骤4：使用false值禁用模型ID为3的模型
r($aiTest->toggleModelTest(4, null)) && p() && e('1');       // 步骤5：使用null值对模型ID为4切换