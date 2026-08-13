#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getLinkedExtra();
timeout=0
cid=14949

- 步骤1：正常情况 @1
- 步骤2：类型转换 @1
- 步骤3：异常输入 @0
- 步骤4：边界值 @1
- 步骤5：业务规则 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
// 准备project表测试数据
zenData('project')->loadYaml('project_getlinkedextra', false, 2)->gen(20);

// 准备productplan表测试数据
zenData('productplan')->loadYaml('productplan_getlinkedextra', false, 2)->gen(10);

// 准备build表测试数据
zenData('build')->loadYaml('build_getlinkedextra', false, 2)->gen(10);

// 准备repohistory表测试数据
zenData('ops_repohistory')->loadYaml('repohistory_getlinkedextra', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：测试productplan类型正常处理（plan转换为productplan）
$action1 = new stdClass();
$action1->extra = '1';
$action1->execution = 6;
$action1->project = 1;
$action1->product = '1';
$action1->objectType = 'story';
r($actionTest->getLinkedExtraTest($action1, 'plan')) && p() && e('1'); // 步骤1：正常情况

// 测试步骤2：测试build类型正常处理（bug转换为build）
$action2 = new stdClass();
$action2->extra = '1';
$action2->execution = 6;
$action2->project = 1;
$action2->product = '1';
$action2->objectType = 'story';
r($actionTest->getLinkedExtraTest($action2, 'bug')) && p() && e('1'); // 步骤2：类型转换

// 测试步骤3：测试空表情况处理（无效的type类型）
$action3 = new stdClass();
$action3->extra = '1';
$action3->execution = 8;
$action3->project = 3;
$action3->product = '3';
$action3->objectType = 'story';
r($actionTest->getLinkedExtraTest($action3, 'nonexistenttype')) && p() && e('0'); // 步骤3：异常输入

// 测试步骤4：测试不存在的对象ID处理（999不存在）
$action4 = new stdClass();
$action4->extra = '999';
$action4->execution = 9;
$action4->project = 4;
$action4->product = '1';
$action4->objectType = 'story';
r($actionTest->getLinkedExtraTest($action4, 'productplan')) && p() && e('1'); // 步骤4：边界值

// 测试步骤5：测试project类型的execution处理（kanban类型）
$action5 = new stdClass();
$action5->extra = '6';  // 执行类型为sprint
$action5->execution = 6;
$action5->project = 1;
$action5->product = '1';
$action5->objectType = 'story';
r($actionTest->getLinkedExtraTest($action5, 'execution')) && p() && e('1'); // 步骤5：业务规则