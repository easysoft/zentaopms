#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->unlinkStoryTest();
cid=1
pid=1

敏捷执行解除关联需求 >> 101,81,324
瀑布执行解除关联需求 >> 131,1,4
看板执行解除关联需求 >> 161,1,4
敏捷执行关联需求统计 >> 2
瀑布执行关联需求统计 >> 2
看板执行关联需求统计 >> 2

*/

$executionIDList = array('101', '131', '161');
$stories         = array('4', '324', '364');
$products        = array('4' => '1', '324' => '81', '364' => '91');
$count           = array('0', '1');

$story   = array('stories' => $stories, 'products' => $products);

$execution = new executionTest();
r($execution->unlinkStoryTest($executionIDList[0], $count[0], $stories[0], $story)) && p('0:project,product,story') && e('101,81,324'); // 敏捷执行解除关联需求
r($execution->unlinkStoryTest($executionIDList[1], $count[0], $stories[1], $story)) && p('0:project,product,story') && e('131,1,4');    // 瀑布执行解除关联需求
r($execution->unlinkStoryTest($executionIDList[2], $count[0], $stories[2], $story)) && p('0:project,product,story') && e('161,1,4');    // 看板执行解除关联需求
r($execution->unlinkStoryTest($executionIDList[0], $count[1], $stories[0], $story)) && p()                          && e('2');          // 敏捷执行关联需求统计
r($execution->unlinkStoryTest($executionIDList[1], $count[1], $stories[1], $story)) && p()                          && e('2');          // 瀑布执行关联需求统计
r($execution->unlinkStoryTest($executionIDList[2], $count[1], $stories[2], $story)) && p()                          && e('2');          // 看板执行关联需求统计

$db->restoreDB();