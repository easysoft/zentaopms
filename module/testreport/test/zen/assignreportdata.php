#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::assignReportData();
timeout=0
cid=0

- 执行testreportTest模块的assignReportDataTest方法，参数是$defaultReportData, 'create', null
 - 属性begin @2024-01-01
 - 属性end @2024-01-31
 - 属性productIdList @1
- 执行testreportTest模块的assignReportDataTest方法，参数是$defaultReportData, 'view', $pager
 - 属性begin @2024-01-01
 - 属性end @2024-01-31
- 执行testreportTest模块的assignReportDataTest方法，参数是array
 - 属性begin @2024-01-01
 - 属性end @2024-01-31
 - 属性productIdList @1
- 执行testreportTest模块的assignReportDataTest方法，参数是$reportDataForEdit, 'edit', null
 - 属性begin @2024-02-01
 - 属性end @2024-02-28
 - 属性productIdList @3
- 执行testreportTest模块的assignReportDataTest方法，参数是$specialReportData, 'create', null
 - 属性tasks @4
 - 属性normalField @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

zenData('testrun');

su('admin');

$testreportTest = new testreportTest();

// 测试步骤1：正常创建模式下分配报告数据
$defaultReportData = array(
    'begin' => '2024-01-01',
    'end' => '2024-01-31',
    'productIdList' => array(1 => 1, 2 => 2),
    'tasks' => array(1 => 'task1', 2 => 'task2'),
    'builds' => array(1 => 'build1'),
    'stories' => array(1 => 'story1'),
    'bugs' => array(),
    'execution' => new stdClass(),
    'owner' => 'admin'
);
$defaultReportData['execution']->id = 1;
$defaultReportData['execution']->name = '测试执行';
r($testreportTest->assignReportDataTest($defaultReportData, 'create', null)) && p('begin,end,productIdList') && e('2024-01-01,2024-01-31,1,2');

// 测试步骤2：正常查看模式下分配报告数据
$pager = new stdClass();
$pager->pageID = 1;
$pager->recTotal = 10;
r($testreportTest->assignReportDataTest($defaultReportData, 'view', $pager)) && p('begin,end') && e('2024-01-01,2024-01-31');

// 测试步骤3：空的报告数据输入情况
r($testreportTest->assignReportDataTest(array(), 'create', null)) && p('begin,end,productIdList') && e('2024-01-01,2024-01-31,1,2');

// 测试步骤4：不同的方法类型验证
$reportDataForEdit = array(
    'begin' => '2024-02-01',
    'end' => '2024-02-28',
    'productIdList' => array(3 => 3),
    'tasks' => array(3 => 'task3'),
    'execution' => new stdClass()
);
$reportDataForEdit['execution']->id = 2;
r($testreportTest->assignReportDataTest($reportDataForEdit, 'edit', null)) && p('begin,end,productIdList') && e('2024-02-01,2024-02-28,3');

// 测试步骤5：包含特殊字段的数据处理
$specialReportData = array(
    'begin' => '2024-03-01',
    'end' => '2024-03-31',
    'productIdList' => array(4 => 4, 5 => 5, 6 => 6),
    'tasks' => array(4 => 'task4', 5 => 'task5'),
    'normalField' => 'normalValue',
    'execution' => new stdClass()
);
$specialReportData['execution']->id = 3;
r($testreportTest->assignReportDataTest($specialReportData, 'create', null)) && p('tasks,normalField') && e('4,5,normalValue');