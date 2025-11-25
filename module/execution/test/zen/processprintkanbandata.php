#!/usr/bin/env php
<?php

/**

title=测试 executionZen::processPrintKanbanData();
timeout=0
cid=16437

- 步骤1：测试空数据列表的处理 @0
- 步骤2：测试没有历史看板数据的执行
 - 属性wait @2
 - 属性doing @2
 - 属性done @1
- 步骤3：测试有历史看板数据的过滤
 - 属性wait @1
 - 属性doing @1
 - 属性done @2
- 步骤4：测试多种类型数据的处理
 - 属性wait @2
 - 属性doing @1
 - 属性done @2
 - 属性closed @3
- 步骤5：测试完全过滤的情况
 - 属性wait @0
 - 属性doing @0
- 步骤6：测试部分过滤的情况
 - 属性wait @1
 - 属性doing @1
 - 属性done @1
- 步骤7：测试无效执行ID的处理
 - 属性wait @2
 - 属性doing @2
 - 属性done @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备
zenData('user')->gen(5);

$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('项目集1,项目1,迭代1,迭代2,迭代3,看板1,看板2,看板3,看板4,看板5');
$execution->type->range('program,project,sprint{3},kanban{5}');
$execution->parent->range('0,1,2{8}');
$execution->status->range('wait{3},doing{7}');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$executionZenTest = new executionZenTest();

// 准备测试数据
$emptyDataList = array();

$dataListWithoutPrev = array(
    'wait' => array('task1' => 'task1', 'task2' => 'task2'),
    'doing' => array('task3' => 'task3', 'task4' => 'task4'),
    'done' => array('task5' => 'task5')
);

$dataListWithPrev = array(
    'wait' => array('task1' => 'task1', 'task2' => 'task2', 'task6' => 'task6'),
    'doing' => array('task3' => 'task3', 'task4' => 'task4', 'task7' => 'task7'),
    'done' => array('task5' => 'task5', 'task8' => 'task8')
);

$multiTypeDataList = array(
    'wait' => array('task1' => 'task1', 'task2' => 'task2'),
    'doing' => array('task3' => 'task3'),
    'done' => array('task4' => 'task4', 'task5' => 'task5'),
    'closed' => array('task6' => 'task6', 'task7' => 'task7', 'task8' => 'task8')
);

$fullFilterDataList = array(
    'wait' => array('task1' => 'task1', 'task2' => 'task2'),
    'doing' => array('task3' => 'task3', 'task4' => 'task4')
);

$partialFilterDataList = array(
    'wait' => array('task1' => 'task1', 'task2' => 'task2', 'task9' => 'task9'),
    'doing' => array('task3' => 'task3', 'task4' => 'task4', 'task10' => 'task10'),
    'done' => array('task11' => 'task11')
);

// 先保存一些看板数据用于测试 - 模拟已有的看板数据
// 使用executionZen的saveKanbanData方法来保存数据
global $tester;
$prevKanbanData = array(
    'wait' => array('task1' => 'task1', 'task2' => 'task2'),
    'doing' => array('task3' => 'task3', 'task4' => 'task4')
);
$tester->loadModel('execution')->saveKanbanData(4, $prevKanbanData);

// 5. 执行测试步骤
r($executionZenTest->processPrintKanbanDataTest(3, $emptyDataList)) && p() && e('0'); // 步骤1：测试空数据列表的处理
r($executionZenTest->processPrintKanbanDataTest(5, $dataListWithoutPrev)) && p('wait,doing,done') && e('2,2,1'); // 步骤2：测试没有历史看板数据的执行
r($executionZenTest->processPrintKanbanDataTest(4, $dataListWithPrev)) && p('wait,doing,done') && e('1,1,2'); // 步骤3：测试有历史看板数据的过滤
r($executionZenTest->processPrintKanbanDataTest(6, $multiTypeDataList)) && p('wait,doing,done,closed') && e('2,1,2,3'); // 步骤4：测试多种类型数据的处理
r($executionZenTest->processPrintKanbanDataTest(4, $fullFilterDataList)) && p('wait,doing') && e('0,0'); // 步骤5：测试完全过滤的情况
r($executionZenTest->processPrintKanbanDataTest(4, $partialFilterDataList)) && p('wait,doing,done') && e('1,1,1'); // 步骤6：测试部分过滤的情况
r($executionZenTest->processPrintKanbanDataTest(999, $dataListWithoutPrev)) && p('wait,doing,done') && e('2,2,1'); // 步骤7：测试无效执行ID的处理