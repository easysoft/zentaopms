#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->setTreePath();
cid=1
pid=1

测试更新计划131的路径 >> 阶段31;,41,131,
测试更新计划132的路径 >> 阶段32;,42,132,
测试更新计划133的路径 >> 阶段33;,43,133,
测试更新计划134的路径 >> 阶段34;,44,134,
测试更新计划135的路径 >> 阶段35;,45,135,

*/
$planIDList = array(131, 132, 133, 134, 135);

$programplan = new programplanTest();

r($programplan->setTreePathTest($planIDList[0])) && p('name;path') && e('阶段31;,41,131,'); // 测试更新计划131的路径
r($programplan->setTreePathTest($planIDList[1])) && p('name;path') && e('阶段32;,42,132,'); // 测试更新计划132的路径
r($programplan->setTreePathTest($planIDList[2])) && p('name;path') && e('阶段33;,43,133,'); // 测试更新计划133的路径
r($programplan->setTreePathTest($planIDList[3])) && p('name;path') && e('阶段34;,44,134,'); // 测试更新计划134的路径
r($programplan->setTreePathTest($planIDList[4])) && p('name;path') && e('阶段35;,45,135,'); // 测试更新计划135的路径