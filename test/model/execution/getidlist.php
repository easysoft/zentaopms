#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getIdListTest();
cid=1
pid=1

敏捷项目下执行id查询 >> 101
瀑布项目下执行id查询 >> 131
看板项目下执行id查询 >> 161
敏捷项目下执行id数量统计 >> 7
敏捷项目下执行id数量统计 >> 8
敏捷项目下执行id数量统计 >> 6

*/

$projectIDList = array('11', '41', '71');
$count         = array('0','1');

$execution = new executionTest();
r($execution->getIdListTest($projectIDList[0],$count[0])) && p('101') && e('101'); // 敏捷项目下执行id查询
r($execution->getIdListTest($projectIDList[1],$count[0])) && p('131') && e('131'); // 瀑布项目下执行id查询
r($execution->getIdListTest($projectIDList[2],$count[0])) && p('161') && e('161'); // 看板项目下执行id查询
r($execution->getIdListTest($projectIDList[0],$count[1])) && p()      && e('7');   // 敏捷项目下执行id数量统计
r($execution->getIdListTest($projectIDList[1],$count[1])) && p()      && e('8');   // 敏捷项目下执行id数量统计
r($execution->getIdListTest($projectIDList[2],$count[1])) && p()      && e('6');   // 敏捷项目下执行id数量统计