#!/usr/bin/env php
<?php

/**

title=测试 aiModel::executePrompt();
timeout=0
cid=0

- 步骤1：空prompt参数测试 @-1
- 步骤2：无效prompt ID测试 @-1
- 步骤3：空object参数测试 @-2
- 步骤4：无效object ID测试 @-6
- 步骤5：有效参数但缺少配置测试 @-6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$promptTable = zenData('ai_prompt');
$promptTable->name->range('测试提示词1,测试提示词2,测试提示词3,测试提示词4,测试提示词5');
$promptTable->module->range('story,task,bug,project,testcase');
$promptTable->source->range(',story.title,task.name,bug.title,project.name');
$promptTable->targetForm->range('story.create,task.edit,bug.edit,project.edit,testcase.create');
$promptTable->status->range('active,active,active,active,draft');
$promptTable->createdBy->range('admin');
$promptTable->createdDate->range('`2023-01-01 10:00:00`');
$promptTable->gen(5);

$modelTable = zenData('ai_model');
$modelTable->type->range('openai,openai,claude');
$modelTable->vendor->range('openai,openai,anthropic');
$modelTable->name->range('测试模型1,测试模型2,测试模型3');
$modelTable->credentials->range('test-key-1,test-key-2,test-key-3');
$modelTable->createdBy->range('admin');
$modelTable->createdDate->range('`2023-01-01 10:00:00`');
$modelTable->enabled->range('1,1,0');
$modelTable->gen(3);

zenData('story')->gen(3);
zenData('storyspec')->gen(3);

su('admin');

$aiTest = new aiTest();

r($aiTest->executePromptTest(null, 1)) && p() && e(-1);        // 步骤1：空prompt参数测试
r($aiTest->executePromptTest(999, 1)) && p() && e(-1);         // 步骤2：无效prompt ID测试
r($aiTest->executePromptTest(1, null)) && p() && e(-2);        // 步骤3：空object参数测试
r($aiTest->executePromptTest(1, 999)) && p() && e(-6);         // 步骤4：无效object ID测试
r($aiTest->executePromptTest(1, 1)) && p() && e(-6);           // 步骤5：有效参数但缺少配置测试