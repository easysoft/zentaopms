#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getInvolvedExecutionListTest();
cid=1
pid=1

根据项目查询执行列表 >> 30,迭代20
根据产品查询执行列表 >> 30,迭代20,sprint
根据项目查询执行数量 >> 1
根据产品查询执行数量 >> 1

*/

$projectIDList = array('0', '30');
$productIDList = array('0', '10');
$limit         = array('0', '2', '10');
$count         = array('0', '1');

$execution = new executionTest();
r($execution->getInvolvedExecutionListTest($projectIDList[1],$limit[0],$productIDList[0],$count[0])) && p('120:project,name')      && e('30,迭代20');         // 根据项目查询执行列表
r($execution->getInvolvedExecutionListTest($projectIDList[0],$limit[0],$productIDList[1],$count[0])) && p('120:project,name,type') && e('30,迭代20,sprint');  // 根据产品查询执行列表
r($execution->getInvolvedExecutionListTest($projectIDList[1],$limit[1],$productIDList[0],$count[1])) && p()                        && e('1');                 // 根据项目查询执行数量
r($execution->getInvolvedExecutionListTest($projectIDList[0],$limit[2],$productIDList[1],$count[1])) && p()                        && e('1');                 // 根据产品查询执行数量