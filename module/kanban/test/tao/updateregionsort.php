#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao->updateRegionSort();
timeout=0
cid=1

- 查看更新之前的排序
 - 属性1 @5
 - 属性2 @10
 - 属性3 @15
 - 属性4 @20
 - 属性5 @25
- 查看更新之后的排序
 - 属性1 @5
 - 属性2 @4
 - 属性3 @3
 - 属性4 @2
 - 属性5 @1
- 更新不存在的ID，排序不变
 - 属性1 @5
 - 属性2 @4
 - 属性3 @3
 - 属性4 @2
 - 属性5 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('kanbanregion')->gen(5);

global $tester;
$regions = $tester->dao->select('id, `order`')->from(TABLE_KANBANREGION)->fetchPairs();

r($regions) && p('1,2,3,4,5') && e('5,10,15,20,25'); // 查看更新之前的排序

$regionIDList = array('5', '4', '3', '2', '1');

$tester->loadModel('kanban')->updateRegionSort($regionIDList);

$regions = $tester->dao->select('id, `order`')->from(TABLE_KANBANREGION)->fetchPairs();
r($regions) && p('1,2,3,4,5') && e('5,4,3,2,1'); // 查看更新之后的排序

$regionIDList = array('10', '11', '12', '13', '14');
$tester->loadModel('kanban')->updateRegionSort($regionIDList);
$regions = $tester->dao->select('id, `order`')->from(TABLE_KANBANREGION)->fetchPairs();
r($regions) && p('1,2,3,4,5') && e('5,4,3,2,1'); // 更新不存在的ID，排序不变