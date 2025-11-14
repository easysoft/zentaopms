#!/usr/bin/env php
<?php

/**

title=测试 executionModel::fetchExecutionsByProjectIdList();
timeout=0
cid=16292

- 测试步骤1：传入空数组参数 @0
- 测试步骤2：传入不存在的项目ID @0
- 测试步骤3：传入单个存在的项目ID @1
- 测试步骤3：验证返回的执行数量 @4
- 测试步骤4：传入多个项目ID(包含存在和不存在的) @3
- 测试步骤4：验证按项目分组 @1
- 测试步骤5：验证返回数据结构 @1
- 测试步骤6：测试type过滤条件 @1
- 测试步骤7：测试multiple和deleted过滤 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3,阶段1,看板1,迭代4,已删除执行,多重执行');
$execution->type->range('project{2},sprint{3},stage{2},kanban{2},sprint');
$execution->status->range('doing{8},closed,suspended');
$execution->parent->range('0,0,1,1,2,1,2,3,1,2');
$execution->project->range('0,0,1,1,2,1,2,3,1,2');
$execution->grade->range('2{2},1{8}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`,`1,6`,`2,7`,`3,8`,`1,9`,`2,10`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->deleted->range('0{9},1');
$execution->vision->range('rnd{10}');
$execution->multiple->range('1{10}');
$execution->gen(10);

su('admin');

$executionTest = new executionTest();

r(count($executionTest->fetchExecutionsByProjectIdListTest(array()))) && p() && e('0');                         // 测试步骤1：传入空数组参数
r(count($executionTest->fetchExecutionsByProjectIdListTest(array(999)))) && p() && e('0');                       // 测试步骤2：传入不存在的项目ID

$singleResult = $executionTest->fetchExecutionsByProjectIdListTest(array(1));
r(count($singleResult)) && p() && e('1');                                                                           // 测试步骤3：传入单个存在的项目ID
r(count($singleResult[1])) && p() && e('4');                                                                         // 测试步骤3：验证返回的执行数量

$multiResult = $executionTest->fetchExecutionsByProjectIdListTest(array(1, 2, 3, 999));
r(count($multiResult)) && p() && e('3');                                                                            // 测试步骤4：传入多个项目ID(包含存在和不存在的)
r(isset($multiResult[1]) && isset($multiResult[2]) && isset($multiResult[3]) && !isset($multiResult[999])) && p() && e('1');  // 测试步骤4：验证按项目分组

$detailResult = $executionTest->fetchExecutionsByProjectIdListTest(array(1));
$execution = current($detailResult[1]);
r(isset($execution->id) && isset($execution->name) && isset($execution->projectName) && isset($execution->projectModel)) && p() && e('1');  // 测试步骤5：验证返回数据结构
r(in_array($execution->type, array('sprint', 'stage', 'kanban'))) && p() && e('1');                               // 测试步骤6：测试type过滤条件
r($execution->multiple == '1' && $execution->deleted == '0') && p() && e('1');                                    // 测试步骤7：测试multiple和deleted过滤