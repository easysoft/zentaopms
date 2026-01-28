#!/usr/bin/env php
<?php

/**

title=测试 programTao::setNoTaskExecution();
timeout=0
cid=17721

- 步骤1：项目1下有执行6没有任务第6条的execution属性 @6
- 步骤2：项目2下所有执行都有任务 @0
- 步骤3：项目3下执行9,10都没有任务
 - 第9条的execution属性 @9
 - 第10条的execution属性 @10
- 步骤4：空项目列表 @0
- 步骤5：不存在的项目ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->project->range('0,0,0,1,1,1,2,2,3,3');
$projectTable->type->range('project,project,project,execution,execution,execution,execution,execution,execution,execution');
$projectTable->name->range('项目1,项目2,项目3,执行1-1,执行1-2,执行1-3,执行2-1,执行2-2,执行3-1,执行3-2');
$projectTable->status->range('doing');
$projectTable->deleted->range('0');
$projectTable->path->range(',1,,2,,3,,1,4,,1,5,,1,6,,2,7,,2,8,,3,9,,3,10,');
$projectTable->gen(10);

$taskTable = zenData('task');
$taskTable->id->range('1-8');
$taskTable->execution->range('4,4,5,5,5,7,7,8');
$taskTable->name->range('任务4-1,任务4-2,任务5-1,任务5-2,任务5-3,任务7-1,任务7-2,任务8-1');
$taskTable->status->range('wait,wait,doing,doing,doing,done,done,closed');
$taskTable->deleted->range('0');
$taskTable->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$programTest = new programTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($programTest->setNoTaskExecutionTest(array(1))) && p('6:execution') && e('6'); // 步骤1：项目1下有执行6没有任务
r($programTest->setNoTaskExecutionTest(array(2))) && p() && e('0'); // 步骤2：项目2下所有执行都有任务
r($programTest->setNoTaskExecutionTest(array(3))) && p('9:execution;10:execution') && e('9;10'); // 步骤3：项目3下执行9,10都没有任务
r($programTest->setNoTaskExecutionTest(array())) && p() && e('0'); // 步骤4：空项目列表
r($programTest->setNoTaskExecutionTest(array(999))) && p() && e('0'); // 步骤5：不存在的项目ID