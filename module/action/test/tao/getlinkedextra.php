#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getLinkedExtra();
timeout=0
cid=0

- 执行actionTest模块的getLinkedExtraTest方法，参数是$action1, 'productplan'  @1
- 执行actionTest模块的getLinkedExtraTest方法，参数是$action2, 'build'  @1
- 执行actionTest模块的getLinkedExtraTest方法，参数是$action3, 'project'  @1
- 执行actionTest模块的getLinkedExtraTest方法，参数是$action4, 'execution'  @1
- 执行actionTest模块的getLinkedExtraTest方法，参数是$action5, 'plan'  @1
- 执行actionTest模块的getLinkedExtraTest方法，参数是$action6, 'bug'  @1
- 执行actionTest模块的getLinkedExtraTest方法，参数是$action7, 'revision'  @0
- 执行actionTest模块的getLinkedExtraTest方法，参数是$action8, 'nonexistenttype'  @0
- 执行actionTest模块的getLinkedExtraTest方法，参数是$action9, 'productplan'  @1
- 执行actionTest模块的getLinkedExtraTest方法，参数是$action10, 'roadmap'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 准备project表测试数据
$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5,执行1,执行2,执行3,执行4,执行5');
$projectTable->type->range('project{5},sprint{5}');
$projectTable->model->range('scrum{5},waterfall{3},kanban{2}');
$projectTable->multiple->range('1{8},0{2}');
$projectTable->gen(10);

// 准备productplan表测试数据
$planTable = zenData('productplan');
$planTable->id->range('1-5');
$planTable->title->range('计划1,计划2,计划3,计划4,计划5');
$planTable->product->range('1-3');
$planTable->gen(5);

// 准备build表测试数据
$buildTable = zenData('build');
$buildTable->id->range('1-5');
$buildTable->name->range('版本1,版本2,版本3,版本4,版本5');
$buildTable->execution->range('1-3');
$buildTable->gen(5);

// 准备repohistory表测试数据
$repohistoryTable = zenData('repohistory');
$repohistoryTable->id->range('1-5');
$repohistoryTable->repo->range('1-3');
$repohistoryTable->revision->range('abcd123456,efgh789012,ijkl345678,mnop901234,qrst567890');
$repohistoryTable->gen(5);

// 准备testtask表测试数据
$testtaskTable = zenData('testtask');
$testtaskTable->id->range('1-5');
$testtaskTable->name->range('测试任务1,测试任务2,测试任务3,测试任务4,测试任务5');
$testtaskTable->product->range('1-3');
$testtaskTable->gen(5);

su('admin');

$actionTest = new actionTest();

// 测试步骤1：正常的productplan类型处理
$action1 = new stdClass();
$action1->extra = '1';
$action1->execution = 1;
$action1->project = 1;
$action1->product = '1';
$action1->objectType = 'story';
r($actionTest->getLinkedExtraTest($action1, 'productplan')) && p() && e('1');

// 测试步骤2：正常的build类型处理  
$action2 = new stdClass();
$action2->extra = '1';
$action2->execution = 1;
$action2->project = 1;
$action2->product = '1';
$action2->objectType = 'story';
r($actionTest->getLinkedExtraTest($action2, 'build')) && p() && e('1');

// 测试步骤3：正常的project类型处理
$action3 = new stdClass();
$action3->extra = '1';
$action3->execution = 1;
$action3->project = 1;
$action3->product = '1';
$action3->objectType = 'story';
r($actionTest->getLinkedExtraTest($action3, 'project')) && p() && e('1');

// 测试步骤4：正常的execution类型处理
$action4 = new stdClass();
$action4->extra = '6';
$action4->execution = 6;
$action4->project = 1;
$action4->product = '1';
$action4->objectType = 'story';
r($actionTest->getLinkedExtraTest($action4, 'execution')) && p() && e('1');

// 测试步骤5：plan类型转换为productplan处理
$action5 = new stdClass();
$action5->extra = '1';
$action5->execution = 1;
$action5->project = 1;
$action5->product = '1';
$action5->objectType = 'story';
r($actionTest->getLinkedExtraTest($action5, 'plan')) && p() && e('1');

// 测试步骤6：bug类型转换为build处理
$action6 = new stdClass();
$action6->extra = '1';
$action6->execution = 1;
$action6->project = 1;
$action6->product = '1';
$action6->objectType = 'story';
r($actionTest->getLinkedExtraTest($action6, 'bug')) && p() && e('1');

// 测试步骤7：revision类型转换为repohistory处理
$action7 = new stdClass();
$action7->extra = '1';
$action7->execution = 1;
$action7->project = 1;
$action7->product = '1';
$action7->objectType = 'story';
r($actionTest->getLinkedExtraTest($action7, 'revision')) && p() && e('0');

// 测试步骤8：无效的type类型处理
$action8 = new stdClass();
$action8->extra = '1';
$action8->execution = 1;
$action8->project = 1;
$action8->product = '1';
$action8->objectType = 'story';
r($actionTest->getLinkedExtraTest($action8, 'nonexistenttype')) && p() && e('0');

// 测试步骤9：空的extra值处理
$action9 = new stdClass();
$action9->extra = '999';
$action9->execution = 1;
$action9->project = 1;
$action9->product = '1';
$action9->objectType = 'story';
r($actionTest->getLinkedExtraTest($action9, 'productplan')) && p() && e('1');

// 测试步骤10：roadmap类型处理
$action10 = new stdClass();
$action10->extra = '1';
$action10->execution = 1;
$action10->project = 1;
$action10->product = '1';
$action10->objectType = 'story';
r($actionTest->getLinkedExtraTest($action10, 'roadmap')) && p() && e('1');