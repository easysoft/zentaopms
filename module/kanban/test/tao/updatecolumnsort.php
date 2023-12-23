#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao->updateColumnSort();
timeout=0
cid=1

- 查看更新之前的排序
 - 属性1 @1
 - 属性2 @2
 - 属性3 @3
 - 属性4 @4
- 查看更新之后的排序
 - 属性1 @4
 - 属性2 @3
 - 属性3 @2
 - 属性4 @1
- 更新不存在的ID，排序不变
 - 属性1 @4
 - 属性2 @3
 - 属性3 @2
 - 属性4 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('kanbancolumn')->gen(200);

global $tester;
$tester->loadModel('kanban');

$columns = $tester->dao->select('id, `order`')->from(TABLE_KANBANCOLUMN)->where('region')->eq('1')->fetchPairs();

r($columns) && p('1,2,3,4') && e('1,2,3,4'); // 查看更新之前的排序

$columnIDList = array('4', '3', '2', '1');

$tester->kanban->updateColumnSort(1, $columnIDList);

$columns = $tester->dao->select('id, `order`')->from(TABLE_KANBANCOLUMN)->where('region')->eq('1')->fetchPairs();
r($columns) && p('1,2,3,4') && e('4,3,2,1'); // 查看更新之后的排序

$columnIDList = array('201', '202');
$tester->kanban->updateColumnSort(1, $columnIDList);
$columns = $tester->dao->select('id, `order`')->from(TABLE_KANBANCOLUMN)->where('region')->eq('1')->fetchPairs();
r($columns) && p('1,2,3,4') && e('4,3,2,1'); // 更新不存在的ID，排序不变
