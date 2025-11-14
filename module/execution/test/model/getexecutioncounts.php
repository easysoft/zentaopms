#!/usr/bin/env php
<?php

/**

title=测试 executionModel::getExecutionCounts();
timeout=0
cid=16312

- 步骤1：项目ID为1的执行数量统计 @5
- 步骤2：项目ID为2的执行数量统计 @3
- 步骤3：项目ID为0查询所有执行数量 @8
- 步骤4：使用browseType为undone的执行数量 @6
- 步骤5：使用browseType为doing的执行数量 @3
- 步骤6：使用browseType为closed的执行数量 @2
- 步骤7：不存在的项目ID查询执行数量 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

zenData('group')->gen(0);
zenData('userview')->gen(0);

$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3,迭代4,迭代5,迭代6,迭代7,迭代8');
$execution->type->range('project{2},sprint{3},kanban{2},stage{3}');
$execution->status->range('doing{5},closed{2},wait{3}');
$execution->parent->range('0,0,1,1,2,1,2,1,2,1');
$execution->project->range('0,0,1,1,2,1,2,1,2,1');
$execution->grade->range('2{2},1{8}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`,`1,6`,`2,7`,`1,8`,`2,9`,`1,10`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->deleted->range('0');
$execution->vision->range('rnd');
$execution->multiple->range('1');
$execution->gen(10);

su('admin');

$executionTest = new executionTest();

r($executionTest->getExecutionCountsTest(1)) && p() && e('5'); // 步骤1：项目ID为1的执行数量统计
r($executionTest->getExecutionCountsTest(2)) && p() && e('3'); // 步骤2：项目ID为2的执行数量统计
r($executionTest->getExecutionCountsTest(0)) && p() && e('8'); // 步骤3：项目ID为0查询所有执行数量
r($executionTest->getExecutionCountsTest(0, 'undone')) && p() && e('6'); // 步骤4：使用browseType为undone的执行数量
r($executionTest->getExecutionCountsTest(0, 'doing')) && p() && e('3'); // 步骤5：使用browseType为doing的执行数量
r($executionTest->getExecutionCountsTest(0, 'closed')) && p() && e('2'); // 步骤6：使用browseType为closed的执行数量
r($executionTest->getExecutionCountsTest(999)) && p() && e('0'); // 步骤7：不存在的项目ID查询执行数量