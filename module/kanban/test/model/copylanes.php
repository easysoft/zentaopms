#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanban')->gen(1);
zdTable('kanbanlane')->gen(1);
zdTable('kanbanregion')->gen(1);

/**

title=测试 kanbanModel->copyLanes();
timeout=0
cid=1

- 正常复制泳道，查看生成的泳道ID
 - 属性1 @2
 - 属性2 @3
 - 属性3 @4
 - 属性4 @5
- 复制空泳道，没有条目被插入 @0
- 复制空泳道，应该返回错误信息第name条的0属性 @『泳道名称』不能为空。

*/

global $tester;
$tester->loadModel('kanban');

$kanban     = (object)array('id' => 1, 'name' => '测试看板');
$regionID   = 1;
$newGroupID = 100001;

$copyLane1  = (object)array('id' => 1, 'title' => '复制泳道1', 'type' => 'common', 'mode' => 'independent');
$copyLane2  = (object)array('id' => 2, 'title' => '复制泳道2', 'type' => 'story', 'mode' => 'independent');
$copyLane3  = (object)array('id' => 3, 'title' => '复制泳道3', 'type' => 'bug', 'mode' => 'independent');
$copyLane4  = (object)array('id' => 4, 'title' => '复制泳道4', 'type' => 'task', 'mode' => 'independent');
$emptyLane  = (object)array('id' => 5, 'title' => '', 'type' => 'common', 'mode' => 'independent');

r($tester->kanban->copyLanes($kanban, [$copyLane1, $copyLane2, $copyLane3, $copyLane4], $regionID, $newGroupID)) && p("1,2,3,4") && e('2,3,4,5'); // 正常复制泳道，查看生成的泳道ID
r($tester->kanban->copyLanes($kanban, [$emptyLane], $regionID, $newGroupID)) && p('') && e('0'); //复制空泳道，没有条目被插入
r(dao::getError()) && p('name:0') && e('『泳道名称』不能为空。'); //复制空泳道，应该返回错误信息