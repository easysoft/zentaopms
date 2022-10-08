#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getRDColumnGroupByRegions();
cid=1
pid=1

测试获取region 1 执行看板泳道列组 >> 4
测试获取region 1 group 1执行看板泳道列组 >> 4
测试获取region 1 group 2执行看板泳道列组 >> 0
测试获取region 2 执行看板泳道列组 >> 4
测试获取region 2 group 2执行看板泳道列组 >> 4
测试获取region 2 group 1执行看板泳道列组 >> 0
测试获取region 3 执行看板泳道列组 >> 8
测试获取region 3 group 3执行看板泳道列组 >> 4
测试获取region 3 group 1执行看板泳道列组 >> 0
测试获取region 4 执行看板泳道列组 >> 12
测试获取region 4 group 5,6执行看板泳道列组 >> 8
测试获取region 5 执行看板泳道列组 >> 16
测试获取region 5 group 8,10执行看板泳道列组 >> 8

*/

$regions     = array('1', '2', '3,4', '5,6,7', '8,9,10,11');
$groupIDList = array('1', '2', '3', '5,6', '8,10');

$kanban = new kanbanTest();

r($kanban->getRDColumnGroupByRegionsTest($regions[0]))                  && p() && e('4');  // 测试获取region 1 执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[0], $groupIDList[0])) && p() && e('4');  // 测试获取region 1 group 1执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[0], $groupIDList[1])) && p() && e('0');  // 测试获取region 1 group 2执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[1]))                  && p() && e('4');  // 测试获取region 2 执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[1], $groupIDList[1])) && p() && e('4');  // 测试获取region 2 group 2执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[1], $groupIDList[0])) && p() && e('0');  // 测试获取region 2 group 1执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[2]))                  && p() && e('8');  // 测试获取region 3 执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[2], $groupIDList[2])) && p() && e('4');  // 测试获取region 3 group 3执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[2], $groupIDList[0])) && p() && e('0');  // 测试获取region 3 group 1执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[3]))                  && p() && e('12'); // 测试获取region 4 执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[3], $groupIDList[3])) && p() && e('8');  // 测试获取region 4 group 5,6执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[4]))                  && p() && e('16'); // 测试获取region 5 执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[4], $groupIDList[4])) && p() && e('8');  // 测试获取region 5 group 8,10执行看板泳道列组