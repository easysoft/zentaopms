#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getPrompts();
timeout=0
cid=15044

- 步骤1：获取所有提示词，应返回10条记录 @10
- 步骤2：按story模块过滤，应返回3条记录 @3
- 步骤3：按active状态过滤，应返回6条记录 @6
- 步骤4：组合过滤，应返回符合条件的记录 @3
- 步骤5：按名称排序，检查第一条记录的名称属性name @Bug润色

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('ai_prompt');
$table->id->range('1-10');
$table->name->range('需求润色,一键拆用例,任务润色,需求转任务,Bug润色,文档润色,Bug转需求,拆分一个子计划,测试提示词1,测试提示词2');
$table->desc->range('测试描述内容{10}');
$table->model->range('0,1,2,3');
$table->module->range('story{3},task{2},bug{2},doc{1},productplan{2}');
$table->source->range(',story.title,story.spec,,task.name,task.desc,,bug.title,bug.steps,');
$table->targetForm->range('story.change,task.edit,bug.edit,doc.edit,story.create');
$table->purpose->range('测试目的内容{10}');
$table->elaboration->range('测试详细说明内容{10}');
$table->role->range('测试角色描述{10}');
$table->characterization->range('测试角色特征{10}');
$table->createdBy->range('admin,system,user1,user2');
$table->createdDate->range('`2023-08-10 10:00:00`,`2023-08-11 11:00:00`,`2023-08-12 12:00:00`');
$table->status->range('active{6},draft{4}');
$table->deleted->range('0');
$table->gen(10);

su('admin');

$aiTest = new aiModelTest();

r(count($aiTest->getPromptsTest())) && p() && e('10'); // 步骤1：获取所有提示词，应返回10条记录
r(count($aiTest->getPromptsTest('story'))) && p() && e('3'); // 步骤2：按story模块过滤，应返回3条记录
r(count($aiTest->getPromptsTest('', 'active'))) && p() && e('6'); // 步骤3：按active状态过滤，应返回6条记录
r(count($aiTest->getPromptsTest('story', 'active'))) && p() && e('3'); // 步骤4：组合过滤，应返回符合条件的记录
r(current($aiTest->getPromptsTest('', '', 'name_asc'))) && p('name') && e('Bug润色'); // 步骤5：按名称排序，检查第一条记录的名称