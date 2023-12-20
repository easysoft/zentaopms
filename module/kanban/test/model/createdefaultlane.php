#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanregion')->gen(0);
zdTable('kanbancolumn')->gen(0);
zdTable('kanbanlane')->gen(0);

/**

title=测试 kanbanModel->createDefaultLane();
timeout=0
cid=1

- 创建region 1 group 1的默认泳道
 - 属性name @默认泳道
 - 属性group @1
 - 属性region @1
 - 属性type @common
- 创建region 2 group 2的默认泳道
 - 属性name @默认泳道
 - 属性group @2
 - 属性region @2
 - 属性type @common
- 创建region 3 group 3的默认泳道
 - 属性name @默认泳道
 - 属性group @3
 - 属性region @3
 - 属性type @common
- 创建region 4 group 4的默认泳道
 - 属性name @默认泳道
 - 属性group @4
 - 属性region @4
 - 属性type @common
- 创建region 5 group 5的默认泳道
 - 属性name @默认泳道
 - 属性group @5
 - 属性region @5
 - 属性type @common

*/

$kanban = new kanbanTest();

$regionIDList = array('1', '2', '3', '4', '5');
$groupIDList  = array('1', '2', '3', '4', '5');

r($kanban->createDefaultLaneTest($regionIDList[0], $groupIDList[0])) && p('name,group,region,type') && e('默认泳道,1,1,common'); // 创建region 1 group 1的默认泳道
r($kanban->createDefaultLaneTest($regionIDList[1], $groupIDList[1])) && p('name,group,region,type') && e('默认泳道,2,2,common'); // 创建region 2 group 2的默认泳道
r($kanban->createDefaultLaneTest($regionIDList[2], $groupIDList[2])) && p('name,group,region,type') && e('默认泳道,3,3,common'); // 创建region 3 group 3的默认泳道
r($kanban->createDefaultLaneTest($regionIDList[3], $groupIDList[3])) && p('name,group,region,type') && e('默认泳道,4,4,common'); // 创建region 4 group 4的默认泳道
r($kanban->createDefaultLaneTest($regionIDList[4], $groupIDList[4])) && p('name,group,region,type') && e('默认泳道,5,5,common'); // 创建region 5 group 5的默认泳道