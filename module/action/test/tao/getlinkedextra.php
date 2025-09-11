#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getLinkedExtra();
timeout=0
cid=0

- 步骤1：execution类型正常情况 @1
- 步骤2：project类型正常情况 @1
- 步骤3：productplan类型正常情况 @1
- 步骤4：build类型正常情况 @1
- 步骤5：revision类型正常情况 @0
- 步骤6：无效表类型情况 @0
- 步骤7：对象不存在情况 @0
- 步骤8：空extra值情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5,执行1,执行2,执行3,执行4,执行5');
$projectTable->type->range('project{5},sprint{5}');
$projectTable->model->range('scrum{5},waterfall{3},kanban{2}');
$projectTable->multiple->range('1{8},0{2}');
$projectTable->gen(10);

$planTable = zenData('productplan');
$planTable->id->range('1-5');
$planTable->title->range('计划1,计划2,计划3,计划4,计划5');
$planTable->product->range('1-3');
$planTable->gen(5);

$buildTable = zenData('build');
$buildTable->id->range('1-5');
$buildTable->name->range('版本1,版本2,版本3,版本4,版本5');
$buildTable->execution->range('1-3');
$buildTable->gen(5);

$repohistoryTable = zenData('repohistory');
$repohistoryTable->id->range('1-5');
$repohistoryTable->repo->range('1-3');
$repohistoryTable->revision->range('abcd123456,efgh789012,ijkl345678,mnop901234,qrst567890');
$repohistoryTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试用的action对象
$action1 = new stdClass();
$action1->extra = '6'; // execution ID
$action1->execution = 6;
$action1->project = 1;
$action1->objectType = 'story';

$action2 = new stdClass();  
$action2->extra = '1'; // project ID
$action2->project = 1;
$action2->objectType = 'story';

$action3 = new stdClass();
$action3->extra = '1'; // plan ID
$action3->objectType = 'story';

$action4 = new stdClass();
$action4->extra = '1'; // build ID
$action4->execution = 1;
$action4->objectType = 'story';

$action5 = new stdClass();
$action5->extra = '1'; // revision ID
$action5->objectType = 'story';

$action6 = new stdClass();
$action6->extra = '1'; // 无效类型测试
$action6->objectType = 'story';

$action7 = new stdClass();
$action7->extra = '999'; // 不存在的对象ID
$action7->objectType = 'story';

$action8 = new stdClass();
$action8->extra = '0'; // 空extra值
$action8->objectType = 'story';

r($actionTest->getLinkedExtraTest($action1, 'execution')) && p() && e('1'); // 步骤1：execution类型正常情况
r($actionTest->getLinkedExtraTest($action2, 'project')) && p() && e('1'); // 步骤2：project类型正常情况  
r($actionTest->getLinkedExtraTest($action3, 'plan')) && p() && e('1'); // 步骤3：productplan类型正常情况
r($actionTest->getLinkedExtraTest($action4, 'build')) && p() && e('1'); // 步骤4：build类型正常情况
r($actionTest->getLinkedExtraTest($action5, 'revision')) && p() && e('0'); // 步骤5：revision类型正常情况
r($actionTest->getLinkedExtraTest($action6, 'invalidtype')) && p() && e('0'); // 步骤6：无效表类型情况
r($actionTest->getLinkedExtraTest($action7, 'execution')) && p() && e('0'); // 步骤7：对象不存在情况
r($actionTest->getLinkedExtraTest($action8, 'execution')) && p() && e('0'); // 步骤8：空extra值情况