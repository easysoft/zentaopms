#!/usr/bin/env php
<?php

/**

title=测试 aiModel::deleteModel();
timeout=0
cid=0

- 步骤1：删除存在的AI模型 @1
- 步骤2：删除不存在的AI模型 @1
- 步骤3：删除已删除的AI模型 @1
- 步骤4：删除无效ID为0的AI模型 @1
- 步骤5：删除负数ID的AI模型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_model');
$table->id->range('1-10');
$table->type->range('chat');
$table->vendor->range('openai,azure,qianwen,hunyuan,moonshot');
$table->credentials->range('{"apiKey":"test123","model":"gpt-4"}');
$table->name->range('GPT-4,Azure OpenAI,千问,混元,月之暗面');
$table->desc->range('测试模型描述');
$table->createdBy->range('admin');
$table->createdDate->range('`2024-01-01 10:00:00`');
$table->enabled->range('1');
$table->deleted->range('0');
$table->gen(5);

$chatTable = zenData('im_chat');
$chatTable->gid->range('group_ai-1,group_ai-2,group_ai-3,normal_group');
$chatTable->name->range('AI Chat 1,AI Chat 2,AI Chat 3,Normal Group');
$chatTable->type->range('group');
$chatTable->gen(4);

$assistantTable = zenData('ai_assistant');
$assistantTable->id->range('1-5');
$assistantTable->modelId->range('1,2,3,1,2');
$assistantTable->name->range('助手1,助手2,助手3,助手4,助手5');
$assistantTable->desc->range('助手描述');
$assistantTable->systemMessage->range('系统消息');
$assistantTable->greetings->range('问候语');
$assistantTable->createdDate->range('`2024-01-01 10:00:00`');
$assistantTable->deleted->range('0');
$assistantTable->gen(5);

su('admin');

$aiTest = new aiTest();

r($aiTest->deleteModelTest(1)) && p() && e(1);                        // 步骤1：删除存在的AI模型
r($aiTest->deleteModelTest(999)) && p() && e(1);                    // 步骤2：删除不存在的AI模型
r($aiTest->deleteModelTest(1)) && p() && e(1);                      // 步骤3：删除已删除的AI模型
r($aiTest->deleteModelTest(0)) && p() && e(1);                      // 步骤4：删除无效ID为0的AI模型
r($aiTest->deleteModelTest(-1)) && p() && e(1);                     // 步骤5：删除负数ID的AI模型