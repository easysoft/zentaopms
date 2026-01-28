#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProjectTeamBlock();
timeout=0
cid=15278

- 步骤1：正常参数调用属性success @1
- 步骤2：空参数调用使用默认值属性success @1
- 步骤3：自定义count参数属性success @1
- 步骤4：指定type参数为wait属性success @1
- 步骤5：指定orderBy参数为name_asc属性success @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('11-20');
$project->project->range('11-20');
$project->name->prefix("项目")->range('11-20');
$project->code->prefix("project")->range('11-20');
$project->model->range("scrum");
$project->auth->range("[]");
$project->path->range("[]");
$project->type->range("project");
$project->grade->range("1");
$project->days->range("1");
$project->status->range("wait{3},doing{4},suspended{2},closed{1}");
$project->desc->range("[]");
$project->budget->range("100000,200000");
$project->budgetUnit->range("CNY");
$project->percent->range("0-0");
$project->openedDate->range("`2023-05-01 10:00:10`");
$project->gen(10);

zenData('team')->gen(20);

$stakeholder = zenData('stakeholder');
$stakeholder->id->range('1-10');
$stakeholder->objectID->range('11-20');
$stakeholder->objectType->range('program,project');
$stakeholder->user->range("admin");
$stakeholder->type->range("inside");
$stakeholder->from->range("[]");
$stakeholder->createdBy->range("admin");
$stakeholder->createdDate->range("`2023-05-01 10:00:10`");
$stakeholder->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$block1 = new stdclass();
$block1->params = new stdclass();
$block1->params->count = 15;
$block1->params->type = 'all';
$block1->params->orderBy = 'id_desc';
r($blockTest->printProjectTeamBlockTest($block1)) && p('success') && e('1'); // 步骤1：正常参数调用

r($blockTest->printProjectTeamBlockTest()) && p('success') && e('1'); // 步骤2：空参数调用使用默认值

$block3 = new stdclass();
$block3->params = new stdclass();
$block3->params->count = 5;
$block3->params->type = 'all';
$block3->params->orderBy = 'id_desc';
r($blockTest->printProjectTeamBlockTest($block3)) && p('success') && e('1'); // 步骤3：自定义count参数

$block4 = new stdclass();
$block4->params = new stdclass();
$block4->params->count = 15;
$block4->params->type = 'wait';
$block4->params->orderBy = 'id_desc';
r($blockTest->printProjectTeamBlockTest($block4)) && p('success') && e('1'); // 步骤4：指定type参数为wait

$block5 = new stdclass();
$block5->params = new stdclass();
$block5->params->count = 15;
$block5->params->type = 'all';
$block5->params->orderBy = 'name_asc';
r($blockTest->printProjectTeamBlockTest($block5)) && p('success') && e('1'); // 步骤5：指定orderBy参数为name_asc