#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getPromptsForTargetForm();
timeout=0
cid=1

- 步骤1：存在匹配 story.create 的智能体数量 @2
- 步骤2：返回结果按 id 倒序，第 1 条 id @3
- 步骤3：draft 状态不返回 @0
- 步骤4：deleted 状态不返回 @0
- 步骤5：不存在的 targetForm 返回空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('ai_agent');
$table->id->range('1-5');
$table->code->range('prompt1,prompt2,prompt3,prompt4,prompt5');
$table->name->range('prompt1,prompt2,prompt3,prompt4,prompt5');
$table->model->range('gpt-4{5}');
$table->knowledgeLib->range('{5}');
$table->module->range('story{5}');
$table->displayPosition->range('form{5}');
$table->actionPurpose->range('story.create,bug.create,story.create,story.create,story.create');
$table->source->range('story.title');
$table->targetForm->range('story.create,bug.create,story.create,story.create,story.create');
$table->purpose->range('表单处理{5}');
$table->status->range('active,active,draft,active,active');
$table->deleted->range('0,0,0,1,0');
$table->createdBy->range('admin');
$table->createdDate->range('`2026-06-22 00:00:00`');
$table->editedBy->range('admin{5}');
$table->editedDate->range('`2026-06-22 00:00:00`');
$table->gen(5);

su('admin');

$aiTest = new aiModelTest();

$storyPrompts = $aiTest->getPromptsForTargetFormTest('story', 'create');
$taskPrompts  = $aiTest->getPromptsForTargetFormTest('task', 'create');
$storyPromptKeys = array_keys($storyPrompts);

r(count($storyPrompts)) && p() && e('2');
r($storyPromptKeys) && p('0') && e('5');
r(isset($storyPrompts[2])) && p() && e('0');
r(in_array(4, array_column($storyPrompts, 'id'))) && p() && e('0');
r(count($taskPrompts)) && p() && e('0');
