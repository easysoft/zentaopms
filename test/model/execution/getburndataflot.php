#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getBurnDataFlotTest();
cid=1
pid=1

敏捷执行查询统计 >> 2
瀑布执行查询统计 >> 2
看板执行查询统计 >> 1

*/

$executionIDList = array('101', '131', '161');
$count           = array('0','1');

$execution = new executionTest();
r($execution->getBurnDataFlotTest($executionIDList[0], $count[1])) && p() && e('2'); // 敏捷执行查询统计
r($execution->getBurnDataFlotTest($executionIDList[1], $count[1])) && p() && e('2'); // 瀑布执行查询统计
r($execution->getBurnDataFlotTest($executionIDList[2], $count[1])) && p() && e('1'); // 看板执行查询统计
