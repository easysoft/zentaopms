#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->processPlan();
cid=1
pid=1

测试计算计划131 >> 131,阶段31,已关闭的正常产品21
测试计算计划132 >> 132,阶段32,已关闭的正常产品22
测试计算计划133 >> 133,阶段33,已关闭的正常产品23
测试计算计划134 >> 134,阶段34,已关闭的正常产品24
测试计算计划135 >> 135,阶段35,已关闭的正常产品25

*/
$planIDList = array(131, 132, 133, 134, 135);

$programplan = new programplanTest();

r($programplan->processPlanTest($planIDList[0])) && p('id,name,productName') && e('131,阶段31,已关闭的正常产品21'); // 测试计算计划131
r($programplan->processPlanTest($planIDList[1])) && p('id,name,productName') && e('132,阶段32,已关闭的正常产品22'); // 测试计算计划132
r($programplan->processPlanTest($planIDList[2])) && p('id,name,productName') && e('133,阶段33,已关闭的正常产品23'); // 测试计算计划133
r($programplan->processPlanTest($planIDList[3])) && p('id,name,productName') && e('134,阶段34,已关闭的正常产品24'); // 测试计算计划134
r($programplan->processPlanTest($planIDList[4])) && p('id,name,productName') && e('135,阶段35,已关闭的正常产品25'); // 测试计算计划135