#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->getMilestones();
cid=1
pid=1

测试获取项目41的里程碑 >> 阶段571,阶段481,阶段391,阶段301,阶段211
测试获取项目52的里程碑 >> 0
测试获取项目43的里程碑 >> 阶段573,阶段483,阶段393,阶段303,阶段213,阶段123,阶段33
测试获取项目54的里程碑 >> 0
测试获取项目45的里程碑 >> 阶段575,阶段485,阶段395,阶段305,阶段215,阶段125,阶段35

*/
$projectIDList = array(41, 52, 43, 54, 45);

$programplan = new programplanTest();

r($programplan->getMilestonesTest($projectIDList[0])) && p() && e('阶段571,阶段481,阶段391,阶段301,阶段211');                // 测试获取项目41的里程碑
r($programplan->getMilestonesTest($projectIDList[1])) && p() && e('0');                                                      // 测试获取项目52的里程碑
r($programplan->getMilestonesTest($projectIDList[2])) && p() && e('阶段573,阶段483,阶段393,阶段303,阶段213,阶段123,阶段33'); // 测试获取项目43的里程碑
r($programplan->getMilestonesTest($projectIDList[3])) && p() && e('0');                                                      // 测试获取项目54的里程碑
r($programplan->getMilestonesTest($projectIDList[4])) && p() && e('阶段575,阶段485,阶段395,阶段305,阶段215,阶段125,阶段35'); // 测试获取项目45的里程碑