#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getPromptById();
timeout=0
cid=15043

- 步骤1：正常情况属性name @需求润色
- 步骤2：不存在的ID @0
- 步骤3：ID为0 @0
- 步骤4：负数ID @0
- 步骤5：非数字ID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_prompt');
$table->id->range('1-10');
$table->name->range('需求润色,任务润色,Bug润色,文档润色,一键拆用例,需求转任务,生成测试,代码审查,数据分析,流程优化');
$table->desc->range('测试描述内容{10}');
$table->model->range('0,1,2,3,4,5');
$table->module->range('story{3},task{2},bug{2},doc{2},testcase{1}');
$table->source->range(',story.title,story.spec,,task.name,task.desc,,bug.title,bug.steps,');
$table->targetForm->range('story.change,task.edit,bug.edit,doc.edit,story.testcasecreate');
$table->purpose->range('测试目的内容{10}');
$table->elaboration->range('测试详细说明内容{10}');
$table->role->range('测试角色描述{10}');
$table->characterization->range('测试角色特征{10}');
$table->createdBy->range('admin,system,user1,user2');
$table->createdDate->range('`2023-08-10 10:00:00`,`2023-08-11 11:00:00`,`2023-08-12 12:00:00`');
$table->status->range('active{8},draft{2}');
$table->deleted->range('0');
$table->gen(10);

su('admin');

$aiTest = new aiTest();

r($aiTest->getPromptByIdTest(1)) && p('name') && e('需求润色'); // 步骤1：正常情况
r($aiTest->getPromptByIdTest(999)) && p() && e('0'); // 步骤2：不存在的ID
r($aiTest->getPromptByIdTest(0)) && p() && e('0'); // 步骤3：ID为0
r($aiTest->getPromptByIdTest(-1)) && p() && e('0'); // 步骤4：负数ID
r($aiTest->getPromptByIdTest('abc')) && p() && e('0'); // 步骤5：非数字ID