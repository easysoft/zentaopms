#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getLaneById();
timeout=0
cid=1

- 测试通过id1获取泳道信息
 - 属性name @默认泳道
 - 属性type @common
 - 属性region @1
 - 属性group @1
- 测试通过id2获取泳道信息
 - 属性name @默认泳道
 - 属性type @common
 - 属性region @2
 - 属性group @2
- 测试通过id3获取泳道信息
 - 属性name @默认泳道
 - 属性type @common
 - 属性region @3
 - 属性group @3
- 测试通过id4获取泳道信息
 - 属性name @默认泳道
 - 属性type @common
 - 属性region @4
 - 属性group @4
- 测试通过id5获取泳道信息
 - 属性name @默认泳道
 - 属性type @common
 - 属性region @5
 - 属性group @5
- 测试通过不存在的id获取泳道信息属性name @0
属性type @0
属性region @0
属性group @0

*/

$laneIDList = array('1', '2', '3', '4', '5', '1000001');

$kanban = new kanbanTest();

r($kanban->getLaneByIdTest($laneIDList[0])) && p('name,type,region,group') && e('默认泳道,common,1,1'); // 测试通过id1获取泳道信息
r($kanban->getLaneByIdTest($laneIDList[1])) && p('name,type,region,group') && e('默认泳道,common,2,2'); // 测试通过id2获取泳道信息
r($kanban->getLaneByIdTest($laneIDList[2])) && p('name,type,region,group') && e('默认泳道,common,3,3'); // 测试通过id3获取泳道信息
r($kanban->getLaneByIdTest($laneIDList[3])) && p('name,type,region,group') && e('默认泳道,common,4,4'); // 测试通过id4获取泳道信息
r($kanban->getLaneByIdTest($laneIDList[4])) && p('name,type,region,group') && e('默认泳道,common,5,5'); // 测试通过id5获取泳道信息
r($kanban->getLaneByIdTest($laneIDList[5])) && p('name,type,region,group') && e('0');                   // 测试通过不存在的id获取泳道信息