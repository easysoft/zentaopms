#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->statRelatedDataTest();
cid=1
pid=1

敏捷执行数据统计 >> 3
瀑布执行数据统计 >> 4
看板执行数据统计 >> 3

*/

$executionIDList = array('101', '131', '161');

$execution = new executionTest();
r($execution->statRelatedDataTest($executionIDList[0])) && p('storyCount') && e('3'); // 敏捷执行数据统计
r($execution->statRelatedDataTest($executionIDList[1])) && p('taskCount')  && e('4'); // 瀑布执行数据统计
r($execution->statRelatedDataTest($executionIDList[2])) && p('bugCount')   && e('3'); // 看板执行数据统计