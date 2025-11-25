#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';
su('admin');

zenData('kanbanlane')->gen(0);
zenData('project')->loadYaml('execution')->gen(110);

/**

title=测试 kanbanModel->createExecutionLane();
timeout=0
cid=16895

- 查看执行101创建的泳道数量 @4
- 查看执行101创建的泳道数量 @4
- 查看执行101创建的泳道数量 @4
- 查看执行101创建的泳道数量 @4
- 查看执行101创建的泳道数量 @4

*/

global $tester,$config;
$tester->loadModel('kanban');
if(isset($config->kanban->default->risk)) unset($config->kanban->default->risk); // 删除风险泳道

$execution = $tester->loadModel('execution')->fetchByID(101);
$tester->loadModel('kanban')->createLaneIfNotExist($execution);
$lanes = $tester->dao->select('*')->from(TABLE_KANBANLANE)->where('execution')->eq(101)->fetchAll();
r(count($lanes)) && p() && e('4'); // 查看执行101创建的泳道数量

$execution = $tester->loadModel('execution')->fetchByID(102);
$tester->loadModel('kanban')->createLaneIfNotExist($execution);
$lanes = $tester->dao->select('*')->from(TABLE_KANBANLANE)->where('execution')->eq(102)->fetchAll();
r(count($lanes)) && p() && e('4'); // 查看执行101创建的泳道数量

$execution = $tester->loadModel('execution')->fetchByID(103);
$tester->loadModel('kanban')->createLaneIfNotExist($execution);
$lanes = $tester->dao->select('*')->from(TABLE_KANBANLANE)->where('execution')->eq(103)->fetchAll();
r(count($lanes)) && p() && e('4'); // 查看执行101创建的泳道数量

$execution = $tester->loadModel('execution')->fetchByID(104);
$tester->loadModel('kanban')->createLaneIfNotExist($execution);
$lanes = $tester->dao->select('*')->from(TABLE_KANBANLANE)->where('execution')->eq(104)->fetchAll();
r(count($lanes)) && p() && e('4'); // 查看执行101创建的泳道数量

$execution = $tester->loadModel('execution')->fetchByID(105);
$tester->loadModel('kanban')->createLaneIfNotExist($execution);
$lanes = $tester->dao->select('*')->from(TABLE_KANBANLANE)->where('execution')->eq(105)->fetchAll();
r(count($lanes)) && p() && e('4'); // 查看执行101创建的泳道数量
