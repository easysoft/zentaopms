#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->getMilestoneByProduct();
cid=1
pid=1

测试获取产品21 项目41的里程碑 >> 阶段121,阶段301,阶段481,阶段31,阶段211,阶段391,阶段571
测试获取产品32 项目52的里程碑 >> 0
测试获取产品23 项目43的里程碑 >> 阶段123,阶段303,阶段483,阶段33,阶段213,阶段393,阶段573
测试获取产品34 项目54的里程碑 >> 0
测试获取产品25 项目45的里程碑 >> 阶段125,阶段305,阶段485,阶段35,阶段215,阶段395,阶段575

*/
$projectIDList = array(41, 52, 43, 54, 45);
$productIDList = array(21, 32, 23, 34, 25);

$programplan = new programplanTest();

r($programplan->getMilestoneByProductTest($productIDList[0], $projectIDList[0])) && p() && e('阶段121,阶段301,阶段481,阶段31,阶段211,阶段391,阶段571'); // 测试获取产品21 项目41的里程碑
r($programplan->getMilestoneByProductTest($productIDList[1], $projectIDList[1])) && p() && e('0');                                                      // 测试获取产品32 项目52的里程碑
r($programplan->getMilestoneByProductTest($productIDList[2], $projectIDList[2])) && p() && e('阶段123,阶段303,阶段483,阶段33,阶段213,阶段393,阶段573'); // 测试获取产品23 项目43的里程碑
r($programplan->getMilestoneByProductTest($productIDList[3], $projectIDList[3])) && p() && e('0');                                                      // 测试获取产品34 项目54的里程碑
r($programplan->getMilestoneByProductTest($productIDList[4], $projectIDList[4])) && p() && e('阶段125,阶段305,阶段485,阶段35,阶段215,阶段395,阶段575'); // 测试获取产品25 项目45的里程碑