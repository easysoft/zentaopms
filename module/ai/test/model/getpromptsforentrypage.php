#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getPromptsForEntryPage();
timeout=0
cid=1

- 步骤1：detail 页面且 method 非 view 返回空 @0
- 步骤2：detail 页面只返回模块匹配智能体数量 @1
- 步骤3：form 页面按 actionPurpose 返回数量 @1
- 步骤4：form 页面不会返回 draft 智能体 @0
- 步骤5：form 页面不会返回 deleted 智能体 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('ai_agent');
$table->id->range('1-5');
$table->code->range('detailPrompt,detailOther,formPrompt,draftPrompt,deletedPrompt');
$table->name->range('detailPrompt,detailOther,formPrompt,draftPrompt,deletedPrompt');
$table->model->range('gpt-4{5}');
$table->knowledgeLib->range('{5}');
$table->module->range('bug,story,story{3}');
$table->source->range('bug.title,bug.steps,story.title,story.title,story.title');
$table->targetForm->range('story.create{5}');
$table->actionPurpose->range('bug.view,story.view,story.create,story.create,story.create');
$table->displayPosition->range('detail,detail,form,form,form');
$table->purpose->range('详情处理,详情处理,表单处理,表单处理,表单处理');
$table->createdBy->range('admin{5}');
$table->status->range('active,active,active,draft,active');
$table->deleted->range('0,0,0,0,1');
$table->createdDate->range('`2026-06-22 00:00:00`');
$table->editedBy->range('admin{5}');
$table->editedDate->range('`2026-06-22 00:00:00`');
$table->gen(5);

su('admin');

$aiTest = new aiModelTest();

$detailWrongMethod = $aiTest->getPromptsForEntryPageTest('bug', 'edit', 'detail');
$detailPrompts     = $aiTest->getPromptsForEntryPageTest('bug', 'view', 'detail');
$formPrompts       = $aiTest->getPromptsForEntryPageTest('story', 'create', 'form');

r(count($detailWrongMethod)) && p() && e('0');
r(count($detailPrompts)) && p() && e('1');
r(count($formPrompts)) && p() && e('1');
r(isset($formPrompts[1])) && p() && e('0');
r($formPrompts) && p('0:name') && e('formPrompt');
