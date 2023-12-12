#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao->updateLaneSort();
timeout=0
cid=1

- 查看更新之前的排序
 - 属性1 @5
 - 属性101 @505
- 查看更新之后的排序
 - 属性1 @2
 - 属性101 @1
- 更新不存在的ID，排序不变
 - 属性1 @2
 - 属性101 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('kanbanlane')->gen(200);

global $tester;
$lanes = $tester->dao->select('id, `order`')->from(TABLE_KANBANLANE)->where('region')->eq('1')->fetchPairs();

r($lanes) && p('1,101') && e('5,505'); // 查看更新之前的排序

$laneIDList = array('101', '1');

$tester->loadModel('kanban')->updateLaneSort(1, $laneIDList);

$lanes = $tester->dao->select('id, `order`')->from(TABLE_KANBANLANE)->where('region')->eq('1')->fetchPairs();
r($lanes) && p('1,101') && e('2,1'); // 查看更新之后的排序

$laneIDList = array('201', '202');
$tester->loadModel('kanban')->updateLaneSort(1, $laneIDList);
$lanes = $tester->dao->select('id, `order`')->from(TABLE_KANBANLANE)->where('region')->eq('1')->fetchPairs();
r($lanes) && p('1,101') && e('2,1'); // 更新不存在的ID，排序不变