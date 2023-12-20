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
zdTable('kanbangroup')->gen(200);

/**

title=测试 kanbanModel->copyRegion();
timeout=0
cid=1

- 复制默认区域1，查看相关字段
 - 属性name @默认区域
 - 属性kanban @11
 - 属性space @6
 - 属性createdBy @admin
- 复制默认区域2，查看相关字段
 - 属性name @默认区域
 - 属性kanban @12
 - 属性space @6
 - 属性createdBy @admin
- 复制默认区域3，查看相关字段
 - 属性name @默认区域
 - 属性kanban @13
 - 属性space @7
 - 属性createdBy @admin
- 复制默认区域4，查看相关字段
 - 属性name @默认区域
 - 属性kanban @14
 - 属性space @7
 - 属性createdBy @admin
- 复制默认区域5，查看相关字段
 - 属性name @默认区域
 - 属性kanban @15
 - 属性space @8
 - 属性createdBy @admin
- 查看复制出来的区域下的分组数量 @5
- 查看复制出来的区域下的分组数量 @5
- 查看复制出来的区域下的分组数量 @5
- 查看复制出来的区域下的分组数量 @5
- 查看复制出来的区域下的分组数量 @5
- 查看复制出来的区域下的泳道数量 @26
- 查看复制出来的区域下的泳道数量 @26
- 查看复制出来的区域下的泳道数量 @26
- 查看复制出来的区域下的泳道数量 @26
- 查看复制出来的区域下的泳道数量 @26
- 查看复制出来的区域下的看板列数量 @178
- 查看复制出来的区域下的看板列数量 @178
- 查看复制出来的区域下的看板列数量 @178
- 查看复制出来的区域下的看板列数量 @178
- 查看复制出来的区域下的看板列数量 @178

*/

global $tester;
$tester->loadModel('kanban');

$kanban1 = $tester->kanban->getById(1);
$kanban2 = $tester->kanban->getById(2);
$kanban3 = $tester->kanban->getById(3);
$kanban4 = $tester->kanban->getById(4);
$kanban5 = $tester->kanban->getById(5);

$kanban = new kanbanTest();

r($kanban->copyRegionTest($kanban1, 11, 1, 'kanban', 'updateTaskCell')) && p('name,kanban,space,createdBy') && e('默认区域,11,6,admin'); // 复制默认区域1，查看相关字段
r($kanban->copyRegionTest($kanban2, 12, 2, 'kanban', 'updateTaskCell')) && p('name,kanban,space,createdBy') && e('默认区域,12,6,admin'); // 复制默认区域2，查看相关字段
r($kanban->copyRegionTest($kanban3, 13, 3, 'kanban', 'updateTaskCell')) && p('name,kanban,space,createdBy') && e('默认区域,13,7,admin'); // 复制默认区域3，查看相关字段
r($kanban->copyRegionTest($kanban4, 14, 4, 'kanban', 'updateTaskCell')) && p('name,kanban,space,createdBy') && e('默认区域,14,7,admin'); // 复制默认区域4，查看相关字段
r($kanban->copyRegionTest($kanban5, 15, 5, 'kanban', 'updateTaskCell')) && p('name,kanban,space,createdBy') && e('默认区域,15,8,admin'); // 复制默认区域5，查看相关字段

r(count($tester->kanban->getGroupGroupByRegions(array(11)), true)) && p() && e('5'); // 查看复制出来的区域下的分组数量
r(count($tester->kanban->getGroupGroupByRegions(array(12)), true)) && p() && e('5'); // 查看复制出来的区域下的分组数量
r(count($tester->kanban->getGroupGroupByRegions(array(13)), true)) && p() && e('5'); // 查看复制出来的区域下的分组数量
r(count($tester->kanban->getGroupGroupByRegions(array(14)), true)) && p() && e('5'); // 查看复制出来的区域下的分组数量
r(count($tester->kanban->getGroupGroupByRegions(array(15)), true)) && p() && e('5'); // 查看复制出来的区域下的分组数量

r(count($tester->kanban->getLaneGroupByRegions(array(11)), true)) && p() && e('26'); // 查看复制出来的区域下的泳道数量
r(count($tester->kanban->getLaneGroupByRegions(array(12)), true)) && p() && e('26'); // 查看复制出来的区域下的泳道数量
r(count($tester->kanban->getLaneGroupByRegions(array(13)), true)) && p() && e('26'); // 查看复制出来的区域下的泳道数量
r(count($tester->kanban->getLaneGroupByRegions(array(14)), true)) && p() && e('26'); // 查看复制出来的区域下的泳道数量
r(count($tester->kanban->getLaneGroupByRegions(array(15)), true)) && p() && e('26'); // 查看复制出来的区域下的泳道数量

r(count($tester->kanban->getColumnGroupByRegions(array(11)), true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量
r(count($tester->kanban->getColumnGroupByRegions(array(12)), true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量
r(count($tester->kanban->getColumnGroupByRegions(array(13)), true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量
r(count($tester->kanban->getColumnGroupByRegions(array(14)), true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量
r(count($tester->kanban->getColumnGroupByRegions(array(15)), true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量