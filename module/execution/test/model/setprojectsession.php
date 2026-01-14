#!/usr/bin/env php
<?php

/**

title=测试 executionModel::setProjectSession();
timeout=0
cid=16365

- 步骤1：正常执行ID @2
- 步骤2：不存在的执行ID @0
- 步骤3：执行ID为0 @0
- 步骤4：负数执行ID @0
- 步骤5：非数字输入 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备用户数据
zenData('user')->gen(5);
su('admin');

// 准备项目执行数据
$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('execution,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->project->range('0{2},2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$executionTester = new executionModelTest();

r($executionTester->setProjectSessionTest(3))  && p() && e('2');  // 步骤1：正常执行ID
r($executionTester->setProjectSessionTest(99)) && p() && e('0');  // 步骤2：不存在的执行ID
r($executionTester->setProjectSessionTest(0))  && p() && e('0');  // 步骤3：执行ID为0
r($executionTester->setProjectSessionTest(-1)) && p() && e('0');  // 步骤4：负数执行ID
r($executionTester->setProjectSessionTestWithStringInput()) && p() && e('0');  // 步骤5：非数字输入