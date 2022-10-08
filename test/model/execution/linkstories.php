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

敏捷执行关联需求 >> 2
瀑布执行关联需求 >> 0
看板执行关联需求 >> 0

*/

$executionIDList = array('101', '131', '161');
$productIDList   = array('1', '0', '51');
$planIDList      = array('100', '0', '52');

$execution = new executionTest();
r($execution->linkStoriesTest($executionIDList[0], $productIDList[0], $planIDList[0])) && p() && e('2'); // 敏捷执行关联需求
r($execution->linkStoriesTest($executionIDList[1], $productIDList[1], $planIDList[1])) && p() && e('0'); // 瀑布执行关联需求
r($execution->linkStoriesTest($executionIDList[2], $productIDList[2], $planIDList[2])) && p() && e('0'); // 看板执行关联需求

$db->restoreDB();
