#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanban')->gen(10);
zdTable('kanbanregion')->gen(5);
zdTable('kanbancell')->gen(100);
zdTable('kanbancolumn')->gen(100);
zdTable('kanbanlane')->gen(100);
zdTable('kanbangroup')->gen(200);

/**

title=测试 kanbanModel->copyRegions();
timeout=0
cid=1

- 复制默认区域1，查看相关字段属性6 @默认区域
- 复制默认区域2，查看相关字段属性7 @默认区域
- 复制默认区域3，查看相关字段属性8 @默认区域
- 复制默认区域4，查看相关字段属性9 @默认区域
- 复制默认区域5，查看相关字段属性10 @默认区域
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

$kanban1 = $tester->kanban->getById(6);
$kanban2 = $tester->kanban->getById(7);
$kanban3 = $tester->kanban->getById(8);
$kanban4 = $tester->kanban->getById(9);
$kanban5 = $tester->kanban->getById(10);

$kanban = new kanbanTest();

r($kanban->copyRegionsTest($kanban1, 1,  'kanban')) && p('6')  && e('默认区域'); // 复制默认区域1，查看相关字段
r($kanban->copyRegionsTest($kanban2, 2,  'kanban')) && p('7')  && e('默认区域'); // 复制默认区域2，查看相关字段
r($kanban->copyRegionsTest($kanban3, 3,  'kanban')) && p('8')  && e('默认区域'); // 复制默认区域3，查看相关字段
r($kanban->copyRegionsTest($kanban4, 4,  'kanban')) && p('9')  && e('默认区域'); // 复制默认区域4，查看相关字段
r($kanban->copyRegionsTest($kanban5, 5,  'kanban')) && p('10') && e('默认区域'); // 复制默认区域5，查看相关字段

r(count($tester->kanban->getGroupGroupByRegions(array(6)),  true)) && p() && e('5'); // 查看复制出来的区域下的分组数量
r(count($tester->kanban->getGroupGroupByRegions(array(7)),  true)) && p() && e('5'); // 查看复制出来的区域下的分组数量
r(count($tester->kanban->getGroupGroupByRegions(array(8)),  true)) && p() && e('5'); // 查看复制出来的区域下的分组数量
r(count($tester->kanban->getGroupGroupByRegions(array(9)),  true)) && p() && e('5'); // 查看复制出来的区域下的分组数量
r(count($tester->kanban->getGroupGroupByRegions(array(10)), true)) && p() && e('5'); // 查看复制出来的区域下的分组数量

r(count($tester->kanban->getLaneGroupByRegions(array(6)),  true)) && p() && e('26'); // 查看复制出来的区域下的泳道数量
r(count($tester->kanban->getLaneGroupByRegions(array(7)),  true)) && p() && e('26'); // 查看复制出来的区域下的泳道数量
r(count($tester->kanban->getLaneGroupByRegions(array(8)),  true)) && p() && e('26'); // 查看复制出来的区域下的泳道数量
r(count($tester->kanban->getLaneGroupByRegions(array(9)),  true)) && p() && e('26'); // 查看复制出来的区域下的泳道数量
r(count($tester->kanban->getLaneGroupByRegions(array(10)), true)) && p() && e('26'); // 查看复制出来的区域下的泳道数量

r(count($tester->kanban->getColumnGroupByRegions(array(6)),  true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量
r(count($tester->kanban->getColumnGroupByRegions(array(7)),  true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量
r(count($tester->kanban->getColumnGroupByRegions(array(8)),  true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量
r(count($tester->kanban->getColumnGroupByRegions(array(9)),  true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量
r(count($tester->kanban->getColumnGroupByRegions(array(10)), true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量