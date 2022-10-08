#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getGroupGroupByRegions();
cid=1
pid=1

获取区域1 2 3的泳道组数量 >> 3
获取区域4,5,6的泳道组数量 >> 3
获取区域7,8,9的泳道组数量 >> 3
获取区域10,11,12的泳道组数量 >> 3
获取区域13,14,15的泳道组数量 >> 3

*/

$regions = array('1,2,3', '4,5,6', '7,8,9', '10,11,12', '13,14,15');

$kanban = new kanbanTest();

r($kanban->getGroupGroupByRegionsTest($regions[0])) && p() && e('3'); //获取区域1 2 3的泳道组数量
r($kanban->getGroupGroupByRegionsTest($regions[1])) && p() && e('3'); //获取区域4,5,6的泳道组数量
r($kanban->getGroupGroupByRegionsTest($regions[2])) && p() && e('3'); //获取区域7,8,9的泳道组数量
r($kanban->getGroupGroupByRegionsTest($regions[3])) && p() && e('3'); //获取区域10,11,12的泳道组数量
r($kanban->getGroupGroupByRegionsTest($regions[4])) && p() && e('3'); //获取区域13,14,15的泳道组数量