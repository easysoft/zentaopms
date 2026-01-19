#!/usr/bin/env php
<?php

/**

title=测试 aiModel::updatePrompt();
timeout=0
cid=15078

- 执行aiTest模块的updatePromptTest方法，参数是$prompt, $originalPrompt  @1
- 执行aiTest模块的updatePromptTest方法，参数是$prompt2, $originalPrompt2  @1
- 执行aiTest模块的updatePromptTest方法，参数是$prompt3, $originalPrompt3  @1
- 执行aiTest模块的updatePromptTest方法，参数是$prompt4, null  @1
- 执行aiTest模块的updatePromptTest方法，参数是$prompt5, null 第name条的0属性 @该名称已使用，请尝试其他名称。
- 执行aiTest模块的updatePromptTest方法，参数是$prompt6, null 第name条的0属性 @『name』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('ai_agent');
$table->id->range('1-10');
$table->name->range('提示1,提示2,提示3,提示4,提示5,提示6,提示7,提示8,提示9,提示10');
$table->desc->range('描述1,描述2,描述3,描述4,描述5,描述6,描述7,描述8,描述9,描述10');
$table->module->range('story,task,bug,user,project{5}');
$table->status->range('draft{5},active{5}');
$table->createdBy->range('admin{10}');
$table->createdDate->range('`2023-01-01 10:00:00`');
$table->editedBy->range('admin');
$table->editedDate->range('`2023-01-01 11:00:00`');
$table->deleted->range('0{10}');
$table->gen(10);

su('admin');

$aiTest = new aiModelTest();

// 测试步骤1：正常更新提示信息
$prompt = new stdClass();
$prompt->id = 1;
$prompt->name = '更新后的提示1';
$prompt->desc = '更新后的描述1';
$prompt->module = 'story';
$prompt->status = 'active';

$originalPrompt = new stdClass();
$originalPrompt->id = 1;
$originalPrompt->name = '提示1';
$originalPrompt->desc = '描述1';
$originalPrompt->module = 'story';
$originalPrompt->status = 'draft';
$originalPrompt->createdBy = 'admin';

r($aiTest->updatePromptTest($prompt, $originalPrompt)) && p() && e('1');

// 测试步骤2：仅修改状态为draft（取消发布）
$prompt2 = new stdClass();
$prompt2->id = 2;
$prompt2->name = '提示2';
$prompt2->desc = '描述2';
$prompt2->module = 'task';
$prompt2->status = 'draft';

$originalPrompt2 = new stdClass();
$originalPrompt2->id = 2;
$originalPrompt2->name = '提示2';
$originalPrompt2->desc = '描述2';
$originalPrompt2->module = 'task';
$originalPrompt2->status = 'active';
$originalPrompt2->createdBy = 'admin';

r($aiTest->updatePromptTest($prompt2, $originalPrompt2)) && p() && e('1');

// 测试步骤3：仅修改状态为active（发布）
$prompt3 = new stdClass();
$prompt3->id = 3;
$prompt3->name = '提示3';
$prompt3->desc = '描述3';
$prompt3->module = 'bug';
$prompt3->status = 'active';

$originalPrompt3 = new stdClass();
$originalPrompt3->id = 3;
$originalPrompt3->name = '提示3';
$originalPrompt3->desc = '描述3';
$originalPrompt3->module = 'bug';
$originalPrompt3->status = 'draft';
$originalPrompt3->createdBy = 'admin';

r($aiTest->updatePromptTest($prompt3, $originalPrompt3)) && p() && e('1');

// 测试步骤4：无效ID更新
$prompt4 = new stdClass();
$prompt4->id = 999;
$prompt4->name = '不存在的提示';
$prompt4->desc = '不存在的描述';
$prompt4->module = 'story';
$prompt4->status = 'active';

r($aiTest->updatePromptTest($prompt4, null)) && p() && e('1');

// 测试步骤5：名称重复验证
$prompt5 = new stdClass();
$prompt5->id = 4;
$prompt5->name = '提示2';
$prompt5->desc = '重复名称测试';
$prompt5->module = 'user';
$prompt5->status = 'active';

r($aiTest->updatePromptTest($prompt5, null)) && p('name:0') && e('该名称已使用，请尝试其他名称。');

// 测试步骤6：必填字段验证
$prompt6 = new stdClass();
$prompt6->id = 5;
$prompt6->name = '';
$prompt6->desc = '空名称测试';
$prompt6->module = 'project';
$prompt6->status = 'active';

r($aiTest->updatePromptTest($prompt6, null)) && p('name:0') && e('『name』不能为空。');