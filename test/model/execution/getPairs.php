#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getPairsTest();
cid=1
pid=1

敏捷项目执行查看 >> 迭代1
瀑布项目执行查看 >> 阶段121
看板项目执行查看 >> 看板61
敏捷项目执行统计 >> 7
敏捷项目执行统计 >> 8
敏捷项目执行统计 >> 6

*/

$projectIDList = array('11', '41', '71');
$count         = array('0','1');
$noRealEnd       = array('realEnd' => '');
$readjustTime    = array('readjustTime' => '1');

$execution = new executionTest();
r($execution->getPairsTest($projectIDList[0],$count[0])) && p('101') && e('迭代1');   // 敏捷项目执行查看
r($execution->getPairsTest($projectIDList[1],$count[0])) && p('221') && e('阶段121'); // 瀑布项目执行查看
r($execution->getPairsTest($projectIDList[2],$count[0])) && p('161') && e('看板61');  // 看板项目执行查看
r($execution->getPairsTest($projectIDList[0],$count[1])) && p()      && e('7');       // 敏捷项目执行统计
r($execution->getPairsTest($projectIDList[1],$count[1])) && p()      && e('8');       // 敏捷项目执行统计
r($execution->getPairsTest($projectIDList[2],$count[1])) && p()      && e('6');       // 敏捷项目执行统计