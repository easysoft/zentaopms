#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
/**

title=测试executionModel->getSwitcher();
cid=16341

- 测试设置迭代仪表盘1.5级下拉 @0
- 测试设置阶段仪表盘1.5级下拉 @0
- 测试设置看板仪表盘1.5级下拉 @0
- 测试设置项目仪表盘1.5级下拉 @1
- 测试设置项目仪表盘1.5级下拉 @1
- 测试设置项目仪表盘1.5级下拉 @1
- 测试设置创建迭代1.5级下拉 @0
- 测试设置创建阶段1.5级下拉 @0
- 测试设置创建看板1.5级下拉 @0
- 测试设置迭代下任务列表1.5级下拉 @1
- 测试设置阶段下任务列表1.5级下拉 @1
- 测试设置看板下任务列表1.5级下拉 @1
- 测试设置迭代详情1.5级下拉 @1
- 测试设置阶段详情1.5级下拉 @1
- 测试设置看板详情1.5级下拉 @1

*/

su('admin');
zenData('project')->loadYaml('execution')->gen(30);

$executionIdList = array(101, 106, 124);
$moduleList      = array('execution', 'project');
$methodList      = array('index', 'create', 'task', 'view');

$executionTester = new executionTest();
r($executionTester->getSwitcherTest($executionIdList[0], $moduleList[0], $methodList[0])) && p() && e('0'); // 测试设置迭代仪表盘1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[1], $moduleList[0], $methodList[0])) && p() && e('0'); // 测试设置阶段仪表盘1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[2], $moduleList[0], $methodList[0])) && p() && e('0'); // 测试设置看板仪表盘1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[0], $moduleList[1], $methodList[0])) && p() && e('1'); // 测试设置项目仪表盘1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[1], $moduleList[1], $methodList[0])) && p() && e('1'); // 测试设置项目仪表盘1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[2], $moduleList[1], $methodList[0])) && p() && e('1'); // 测试设置项目仪表盘1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[0], $moduleList[0], $methodList[1])) && p() && e('0'); // 测试设置创建迭代1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[1], $moduleList[0], $methodList[1])) && p() && e('0'); // 测试设置创建阶段1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[2], $moduleList[0], $methodList[1])) && p() && e('0'); // 测试设置创建看板1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[0], $moduleList[0], $methodList[2])) && p() && e('1'); // 测试设置迭代下任务列表1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[1], $moduleList[0], $methodList[2])) && p() && e('1'); // 测试设置阶段下任务列表1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[2], $moduleList[0], $methodList[2])) && p() && e('1'); // 测试设置看板下任务列表1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[0], $moduleList[0], $methodList[3])) && p() && e('1'); // 测试设置迭代详情1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[1], $moduleList[0], $methodList[3])) && p() && e('1'); // 测试设置阶段详情1.5级下拉
r($executionTester->getSwitcherTest($executionIdList[2], $moduleList[0], $methodList[3])) && p() && e('1'); // 测试设置看板详情1.5级下拉
