#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getBurnDataFlotTest();
cid=1
pid=1

敏捷执行查询统计 >> 3
瀑布执行查询统计 >> 3
看板执行查询统计 >> 0

*/

$executionIDList = array('101', '131', '161');

$execution = new executionTest();
r($execution->getBurnDataFlotTest($executionIDList[0])) && p() && e('3'); // 敏捷执行查询统计
r($execution->getBurnDataFlotTest($executionIDList[1])) && p() && e('3'); // 瀑布执行查询统计
r($execution->getBurnDataFlotTest($executionIDList[2])) && p() && e('0'); // 看板执行查询统计
