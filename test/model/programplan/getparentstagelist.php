#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->getParentStageList();
cid=1
pid=1

测试查询项目41 计划701 产品21的父阶段信息 >> 无,阶段31
测试查询项目42 计划702 产品22的父阶段信息 >> 无,阶段32
测试查询项目43 计划703 产品23的父阶段信息 >> 无,阶段33
测试查询项目44 计划704 产品24的父阶段信息 >> 无,阶段34
测试查询项目45 计划705 产品25的父阶段信息 >> 无,阶段35

*/
$projectIDList = array(41, 42, 43, 44, 45);
$planIDList    = array(701, 702, 703, 704, 705);
$productIDList = array(21, 22, 23, 24, 25);

$programplan = new programplanTest();

r($programplan->getParentStageListTest($projectIDList[0], $planIDList[0], $productIDList[0])) && p() && e('无,阶段31'); // 测试查询项目41 计划701 产品21的父阶段信息
r($programplan->getParentStageListTest($projectIDList[1], $planIDList[1], $productIDList[1])) && p() && e('无,阶段32'); // 测试查询项目42 计划702 产品22的父阶段信息
r($programplan->getParentStageListTest($projectIDList[2], $planIDList[2], $productIDList[2])) && p() && e('无,阶段33'); // 测试查询项目43 计划703 产品23的父阶段信息
r($programplan->getParentStageListTest($projectIDList[3], $planIDList[3], $productIDList[3])) && p() && e('无,阶段34'); // 测试查询项目44 计划704 产品24的父阶段信息
r($programplan->getParentStageListTest($projectIDList[4], $planIDList[4], $productIDList[4])) && p() && e('无,阶段35'); // 测试查询项目45 计划705 产品25的父阶段信息