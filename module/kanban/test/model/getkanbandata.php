#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanban')->gen(5);
zdTable('kanbanregion')->gen(20);
zdTable('kanbancell')->gen(100);
zdTable('kanbancolumn')->gen(100);
zdTable('kanbanlane')->gen(100);

/**

title=测试 kanbanModel->getKanbanData();
timeout=0
cid=1

- 查看获取到的kanban1下的列数据
 - 第0条的title属性 @未开始
 - 第0条的type属性 @column1
- 查看获取到的kanban2下的泳道数据
 - 第0条的title属性 @默认泳道
 - 第0条的type属性 @common
- 查看获取到的kanban3下的区域数据 @region3
- 查看获取到的kanban4下的列数据
 - 第0条的title属性 @未开始
 - 第0条的type属性 @column13
- 查看获取到的kanban5下的泳道数据
 - 第0条的title属性 @默认泳道
 - 第0条的type属性 @common
- 测试获取到的kanban下的数据数量 @101
- 测试获取到的kanban下的数据数量 @70
- 测试获取到的kanban下的数据数量 @70
- 测试获取到的kanban下的数据数量 @70
- 测试获取到的kanban下的数据数量 @70

*/

global $tester;
$tester->loadModel('kanban');

r($tester->kanban->getKanbanData(1)[0]['items'][0]['data']['cols'])  && p('0:title,type') && e('未开始,column1');  // 查看获取到的kanban1下的列数据
r($tester->kanban->getKanbanData(2)[0]['items'][0]['data']['lanes']) && p('0:title,type') && e('默认泳道,common'); // 查看获取到的kanban2下的泳道数据
r($tester->kanban->getKanbanData(3)[0]['key'])                       && p('')             && e('region3');         // 查看获取到的kanban3下的区域数据
r($tester->kanban->getKanbanData(4)[0]['items'][0]['data']['cols'])  && p('0:title,type') && e('未开始,column13'); // 查看获取到的kanban4下的列数据
r($tester->kanban->getKanbanData(5)[0]['items'][0]['data']['lanes']) && p('0:title,type') && e('默认泳道,common'); // 查看获取到的kanban5下的泳道数据

r(count($tester->kanban->getKanbanData(1), true)) && p() && e('101'); // 测试获取到的kanban下的数据数量
r(count($tester->kanban->getKanbanData(2), true)) && p() && e('70');  // 测试获取到的kanban下的数据数量
r(count($tester->kanban->getKanbanData(3), true)) && p() && e('70');  // 测试获取到的kanban下的数据数量
r(count($tester->kanban->getKanbanData(4), true)) && p() && e('70');  // 测试获取到的kanban下的数据数量
r(count($tester->kanban->getKanbanData(5), true)) && p() && e('70');  // 测试获取到的kanban下的数据数量