#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->linkStoryTest();
cid=1
pid=1

敏捷执行关联需求 >> 101,1,4
瀑布执行关联需求 >> 131,1,4
看板执行关联需求 >> 161,1,4
敏捷执行关联需求统计 >> 3
瀑布执行关联需求统计 >> 3
看板执行关联需求统计 >> 3

*/

$executionIDList = array('101', '131', '161');
$stories         = array('4', '324', '364');
$products        = array('4' => '1', '324' => '81', '364' => '91');
$count           = array('0','1');

$story   = array('stories' => $stories, 'products' => $products);

$execution = new executionTest();
r($execution->linkStoryTest($executionIDList[0], $count[0], $story)) && p('0:project,product,story') && e('101,1,4'); // 敏捷执行关联需求
r($execution->linkStoryTest($executionIDList[1], $count[0], $story)) && p('0:project,product,story') && e('131,1,4'); // 瀑布执行关联需求
r($execution->linkStoryTest($executionIDList[2], $count[0], $story)) && p('0:project,product,story') && e('161,1,4'); // 看板执行关联需求
r($execution->linkStoryTest($executionIDList[0], $count[1], $story)) && p()                          && e('3');       // 敏捷执行关联需求统计
r($execution->linkStoryTest($executionIDList[1], $count[1], $story)) && p()                          && e('3');       // 瀑布执行关联需求统计
r($execution->linkStoryTest($executionIDList[2], $count[1], $story)) && p()                          && e('3');       // 看板执行关联需求统计

$db->restoreDB();