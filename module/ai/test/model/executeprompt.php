#!/usr/bin/env php
<?php

/**

title=测试 aiModel::executePrompt();
timeout=0
cid=15022

- 步骤1：空prompt参数测试 @-1
- 步骤2：无效prompt ID测试 @-1
- 步骤3：空object参数测试 @-2
- 步骤4：无效object ID测试 @-2
- 步骤5：有效参数但缺少模型配置测试 @-4
- 步骤6：有效参数但数据序列化失败测试 @-3
- 步骤7：有效参数但schema配置错误测试 @-5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 创建ai_agent测试数据
$promptTable = zenData('ai_agent');
$promptTable->name->range('测试提示词1,测试提示词2,测试提示词3,测试提示词4,测试提示词5');
$promptTable->module->range('story,task,bug,project,testcase');
$promptTable->source->range('story.title,task.name,bug.title,project.name,testcase.title');
$promptTable->targetForm->range('story.create,task.edit,bug.edit,project.edit,invalid.form');
$promptTable->status->range('active,active,active,active,active');
$promptTable->model->range('1,1,999,1,1');
$promptTable->createdBy->range('admin');
$promptTable->createdDate->range('`2023-01-01 10:00:00`');
$promptTable->deleted->range('0');
$promptTable->gen(5);

// 创建ai_model测试数据
$modelTable = zenData('ai_model');
$modelTable->type->range('openai,claude,gemini');
$modelTable->vendor->range('openai,anthropic,google');
$modelTable->name->range('测试模型1,测试模型2,测试模型3');
$modelTable->credentials->range('test-key-1,test-key-2,test-key-3');
$modelTable->createdBy->range('admin');
$modelTable->createdDate->range('`2023-01-01 10:00:00`');
$modelTable->enabled->range('1,1,1');
$modelTable->deleted->range('0');
$modelTable->gen(3);

// 创建基础测试数据
zenData('story')->gen(3);
zenData('storyspec')->gen(3);
zenData('task')->gen(3);
zenData('bug')->gen(3);
zenData('project')->gen(3);

su('admin');

$aiTest = new aiModelTest();

r($aiTest->executePromptTest(null, 1)) && p() && e(-1);        // 步骤1：空prompt参数测试
r($aiTest->executePromptTest(999, 1)) && p() && e(-1);         // 步骤2：无效prompt ID测试
r($aiTest->executePromptTest(1, null)) && p() && e(-2);        // 步骤3：空object参数测试
r($aiTest->executePromptTest(1, 999)) && p() && e(-2);         // 步骤4：无效object ID测试
r($aiTest->executePromptTest(3, 1)) && p() && e(-4);           // 步骤5：有效参数但缺少模型配置测试
r($aiTest->executePromptTest(2, 900)) && p() && e(-3);         // 步骤6：有效参数但数据序列化失败测试
r($aiTest->executePromptTest(5, 1)) && p() && e(-5);           // 步骤7：有效参数但schema配置错误测试