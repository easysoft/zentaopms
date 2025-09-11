#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getRiskCardMenu();
timeout=0
cid=0

- 步骤1：空风险列表返回0个菜单 @0
- 步骤2：单个风险对象返回1个菜单 @1
- 步骤3：多个风险对象返回2个菜单 @2
- 步骤4：挂起状态风险返回1个菜单 @1
- 步骤5：取消状态风险返回1个菜单 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('risk');
$table->id->range('1-10');
$table->project->range('1-5');
$table->execution->range('101-110');
$table->name->range('1-10')->prefix('测试风险');
$table->source->range('business,team,logistic,manage,sourcing');
$table->category->range('technical,manage,business,requirement,resource');
$table->strategy->range('avoidance,mitigation,transference,acceptance');
$table->status->range('active,closed,hangup,canceled');
$table->impact->range('1-5');
$table->probability->range('1-5');
$table->rate->range('1-25');
$table->pri->range('high,middle,low');
$table->assignedTo->range('admin,user1,user2');
$table->createdBy->range('admin,user1,user2');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($kanbanTest->getRiskCardMenuTest(array()))) && p() && e('0'); // 步骤1：空风险列表返回0个菜单
r(count($kanbanTest->getRiskCardMenuTest(array((object)array('id' => 1, 'status' => 'active'))))) && p() && e('1'); // 步骤2：单个风险对象返回1个菜单
r(count($kanbanTest->getRiskCardMenuTest(array((object)array('id' => 1, 'status' => 'active'), (object)array('id' => 2, 'status' => 'closed'))))) && p() && e('2'); // 步骤3：多个风险对象返回2个菜单
r(count($kanbanTest->getRiskCardMenuTest(array((object)array('id' => 3, 'status' => 'hangup'))))) && p() && e('1'); // 步骤4：挂起状态风险返回1个菜单
r(count($kanbanTest->getRiskCardMenuTest(array((object)array('id' => 4, 'status' => 'canceled'))))) && p() && e('1'); // 步骤5：取消状态风险返回1个菜单