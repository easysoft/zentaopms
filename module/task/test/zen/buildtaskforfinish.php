#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTaskForFinish();
timeout=0
cid=18911

- 步骤1：正常情况
 - 属性status @done
 - 属性consumed @3
- 步骤2：零日期处理 - 检查非空属性realStarted @~~
- 步骤3：消耗工时验证第currentConsumed条的0属性 @总计消耗为0时不能完成任务，请填写本次消耗工时
- 步骤4：日期验证第finishedDate条的0属性 @实际完成不能小于实际开始
- 步骤5：正常完成验证属性status @done

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('task');
$table->id->range('1-10');
$table->name->range('测试任务{1-10}');
$table->status->range('doing{5},wait{3},pause{2}');
$table->consumed->range('0{3},1-5{7}');
$table->openedBy->range('admin,user1,user2');
$table->execution->range('1-3');
$table->project->range('1');
$table->type->range('devel{7},test{2},design{1}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$oldTask1 = new stdclass();
$oldTask1->id = 1;
$oldTask1->consumed = 2;
$oldTask1->realStarted = '2024-01-01 09:00:00';
$oldTask1->openedBy = 'admin';
$_POST = array('realStarted' => '2024-01-01 09:00:00', 'finishedDate' => '2024-01-02 18:00:00', 'currentConsumed' => 1);
r($taskZenTest->buildTaskForFinishTest($oldTask1)) && p('status,consumed') && e('done,3'); // 步骤1：正常情况

$oldTask2 = new stdclass();
$oldTask2->id = 2;
$oldTask2->consumed = 0;
$oldTask2->realStarted = null;
$oldTask2->openedBy = 'user1';
$_POST = array('realStarted' => '', 'finishedDate' => '2024-01-02 18:00:00', 'currentConsumed' => 2);
r($taskZenTest->buildTaskForFinishTest($oldTask2)) && p('realStarted') && e('~~'); // 步骤2：零日期处理 - 检查非空

$oldTask3 = new stdclass();
$oldTask3->id = 3;
$oldTask3->consumed = 0;
$oldTask3->realStarted = '2024-01-01 09:00:00';
$oldTask3->openedBy = 'user2';
$_POST = array('realStarted' => '2024-01-01 09:00:00', 'finishedDate' => '2024-01-02 18:00:00', 'currentConsumed' => '');
r($taskZenTest->buildTaskForFinishTest($oldTask3)) && p('currentConsumed:0') && e('总计消耗为0时不能完成任务，请填写本次消耗工时'); // 步骤3：消耗工时验证

$oldTask4 = new stdclass();
$oldTask4->id = 4;
$oldTask4->consumed = 1;
$oldTask4->realStarted = '2024-01-02 10:00:00';
$oldTask4->openedBy = 'admin';
$_POST = array('realStarted' => '2024-01-02 10:00:00', 'finishedDate' => '2024-01-02 08:00:00', 'currentConsumed' => 1);
r($taskZenTest->buildTaskForFinishTest($oldTask4)) && p('finishedDate:0') && e('实际完成不能小于实际开始'); // 步骤4：日期验证

$oldTask5 = new stdclass();
$oldTask5->id = 5;
$oldTask5->consumed = 1;
$oldTask5->realStarted = '2024-01-01 09:00:00';
$oldTask5->openedBy = 'admin';
$_POST = array('realStarted' => '2024-01-01 09:00:00', 'finishedDate' => '2024-01-02 18:00:00', 'currentConsumed' => 1);
r($taskZenTest->buildTaskForFinishTest($oldTask5)) && p('status') && e('done'); // 步骤5：正常完成验证