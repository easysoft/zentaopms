#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbangroup')->gen(20);

/**

title=测试 kanbanModel->getGroupGroupByRegions();
timeout=0
cid=1

- 获取区域1 2 3的泳道组数量 @3
- 获取区域4,5,6的泳道组数量 @3
- 获取区域7,8,9的泳道组数量 @3
- 获取区域10,11,12的泳道组数量 @3
- 获取区域13,14,15的泳道组数量 @3

*/

$regions = array(array(1,2,3), array(4,5,6), array(7,8,9), array(10,11,12), array(13,14,15));

$kanban = new kanbanTest();

r($kanban->getGroupGroupByRegionsTest($regions[0])) && p() && e('3'); //获取区域1 2 3的泳道组数量
r($kanban->getGroupGroupByRegionsTest($regions[1])) && p() && e('3'); //获取区域4,5,6的泳道组数量
r($kanban->getGroupGroupByRegionsTest($regions[2])) && p() && e('3'); //获取区域7,8,9的泳道组数量
r($kanban->getGroupGroupByRegionsTest($regions[3])) && p() && e('3'); //获取区域10,11,12的泳道组数量
r($kanban->getGroupGroupByRegionsTest($regions[4])) && p() && e('3'); //获取区域13,14,15的泳道组数量