#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->getPlans();
cid=1
pid=1

测试获取执行41 产品21的计划键值对 >> ,阶段31,阶段121,阶段211,阶段301,阶段391,阶段481,阶段571
测试获取执行42 产品22的计划键值对 >> ,阶段32,阶段122,阶段212,阶段302,阶段392,阶段482,阶段572
测试获取执行43 产品23的计划键值对 >> ,阶段33,阶段123,阶段213,阶段303,阶段393,阶段483,阶段573
测试获取执行44 产品24的计划键值对 >> ,阶段34,阶段124,阶段214,阶段304,阶段394,阶段484,阶段574
测试获取执行45 产品25的计划键值对 >> ,阶段35,阶段125,阶段215,阶段305,阶段395,阶段485,阶段575

*/
$executionIDList = array(41, 42, 43, 44, 45);
$productIDList   = array(21, 22, 23, 24, 25);

$programplan = new programplanTest();

r($programplan->getPlansTest($executionIDList[0], $productIDList[0])) && p() && e(',阶段31,阶段121,阶段211,阶段301,阶段391,阶段481,阶段571'); // 测试获取执行41 产品21的计划键值对
r($programplan->getPlansTest($executionIDList[1], $productIDList[1])) && p() && e(',阶段32,阶段122,阶段212,阶段302,阶段392,阶段482,阶段572'); // 测试获取执行42 产品22的计划键值对
r($programplan->getPlansTest($executionIDList[2], $productIDList[2])) && p() && e(',阶段33,阶段123,阶段213,阶段303,阶段393,阶段483,阶段573'); // 测试获取执行43 产品23的计划键值对
r($programplan->getPlansTest($executionIDList[3], $productIDList[3])) && p() && e(',阶段34,阶段124,阶段214,阶段304,阶段394,阶段484,阶段574'); // 测试获取执行44 产品24的计划键值对
r($programplan->getPlansTest($executionIDList[4], $productIDList[4])) && p() && e(',阶段35,阶段125,阶段215,阶段305,阶段395,阶段485,阶段575'); // 测试获取执行45 产品25的计划键值对