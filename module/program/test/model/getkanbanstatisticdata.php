#!/usr/bin/env php
<?php
/**

title=测试 programModel::getKanbanStatisticData();
timeout=0
cid=17685

- 获取项目集的产品数量 @9
- 获取项目集的计划数量 @0
- 获取项目集的发布数量 @0
- 获取项目集的项目数量 @0
- 获取项目集的进行中的执行数量 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');

zenData('project')->loadYaml('program')->gen(30);
zenData('product')->loadYaml('product')->gen(30);
zenData('task')->gen(0);
zenData('projectproduct')->gen(0);
zenData('productplan')->gen(0);
zenData('release')->gen(0);
zenData('team')->gen(0);

global $app;
$app->rawModule = 'program';

$programTester = new programModelTest();
$statistic     = $programTester->getKanbanStatisticDataTest();

r(count($statistic[0])) && p() && e('9');  // 获取项目集的产品数量
r(count($statistic[1])) && p() && e('0');  // 获取项目集的计划数量
r(count($statistic[2])) && p() && e('0');  // 获取项目集的发布数量
r(count($statistic[3])) && p() && e('0');  // 获取项目集的项目数量
r(count($statistic[4])) && p() && e('2');  // 获取项目集的进行中的执行数量
