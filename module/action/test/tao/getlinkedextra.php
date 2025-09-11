#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getLinkedExtra();
timeout=0
cid=0

- 步骤1：execution类型检查失败情况 @0
- 步骤2：正常project类型 @1
- 步骤3：正常plan类型 @1
- 步骤4：正常build类型 @1
- 步骤5：无效类型 @0
- 步骤6：execution边界情况 @0
- 步骤7：revision类型映射测试 @0
- 步骤8：bug类型映射到build测试 @1
- 步骤9：kanban类型的execution处理 @0
- 步骤10：roadmap类型处理 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('执行1,执行2,执行3,执行4,执行5,执行6,看板执行1,看板执行2,看板执行3,看板执行4');
$execution->type->range('project{5},project{1},kanban{4}');
$execution->multiple->range('1{10}');
$execution->gen(10);

$project = zenData('project');
$project->id->range('11-20');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project');
$project->model->range('scrum{5},kanban{3},waterfall{2}');
$project->multiple->range('1{5},0{5}');
$project->gen(10);

$productplan = zenData('productplan');
$productplan->id->range('1-5');
$productplan->title->range('计划1,计划2,计划3,计划4,计划5');
$productplan->gen(5);

$build = zenData('build');
$build->id->range('1-5');
$build->name->range('构建1,构建2,构建3,构建4,构建5');
$build->execution->range('1-5');
$build->gen(5);

$repo = zenData('repohistory');
$repo->id->range('1-5');
$repo->repo->range('1-5');
$repo->revision->range('abc123def4,def456ghi7,ghi789jkl0,jkl012mno3,mno345pqr6');
$repo->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$action1 = new stdClass();
$action1->extra = '1';
$action1->execution = '1';
$action1->objectType = 'story';
r($actionTest->getLinkedExtraTest($action1, 'execution')) && p() && e('0'); // 步骤1：execution类型检查失败情况

$action2 = new stdClass();
$action2->extra = '11';
$action2->project = '11';
$action2->objectType = 'story';
r($actionTest->getLinkedExtraTest($action2, 'project')) && p() && e('1'); // 步骤2：正常project类型

$action3 = new stdClass();
$action3->extra = '1';
$action3->objectType = 'story';
r($actionTest->getLinkedExtraTest($action3, 'plan')) && p() && e('1'); // 步骤3：正常plan类型

$action4 = new stdClass();
$action4->extra = '1';
$action4->objectType = 'story';
r($actionTest->getLinkedExtraTest($action4, 'build')) && p() && e('1'); // 步骤4：正常build类型

$action5 = new stdClass();
$action5->extra = '999';
$action5->objectType = 'story';
r($actionTest->getLinkedExtraTest($action5, 'invalidtype')) && p() && e('0'); // 步骤5：无效类型

$action6 = new stdClass();
$action6->extra = '6';
$action6->objectType = 'story';
r($actionTest->getLinkedExtraTest($action6, 'execution')) && p() && e('0'); // 步骤6：execution边界情况

$action7 = new stdClass();
$action7->extra = '1';
$action7->objectType = 'story';
r($actionTest->getLinkedExtraTest($action7, 'revision')) && p() && e('0'); // 步骤7：revision类型映射测试

$action8 = new stdClass();
$action8->extra = '1';
$action8->objectType = 'story';
r($actionTest->getLinkedExtraTest($action8, 'bug')) && p() && e('1'); // 步骤8：bug类型映射到build测试

$action9 = new stdClass();
$action9->extra = '7';
$action9->execution = '7';
$action9->objectType = 'story';
r($actionTest->getLinkedExtraTest($action9, 'kanban')) && p() && e('0'); // 步骤9：kanban类型的execution处理

$action10 = new stdClass();
$action10->extra = '1';
$action10->objectType = 'story';
r($actionTest->getLinkedExtraTest($action10, 'roadmap')) && p() && e('1'); // 步骤10：roadmap类型处理