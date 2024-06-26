#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel->sortGroup();
timeout=0
cid=1

- 查看更新之前的排序
 - 属性1 @5
 - 属性101 @ 505
- 查看更新之后的排序
 - 属性1 @2
 - 属性101 @1
- 更新不存在的ID，排序不变
 - 属性1 @2
 - 属性101 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('kanbanregion')->gen(5);
zenData('kanbangroup')->gen(200);

global $tester;
$tester->loadModel('kanban');

$groups = $tester->dao->select('id, `order`')->from(TABLE_KANBANGROUP)->where('`region`')->eq('1')->fetchPairs();

r($groups) && p('1,101') && e('5, 505'); // 查看更新之前的排序

$tester->kanban->sortGroup(1, array('101', '1'));

$groups = $tester->dao->select('id, `order`')->from(TABLE_KANBANGROUP)->where('`region`')->eq('1')->fetchPairs();
r($groups) && p('1,101') && e('2,1'); // 查看更新之后的排序

$tester->kanban->sortGroup(1, array('2', '3'));
$groups = $tester->dao->select('id, `order`')->from(TABLE_KANBANGROUP)->where('`region`')->eq('1')->fetchPairs();
r($groups) && p('1,101') && e('2,1'); // 更新不存在的ID，排序不变