#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getKanbanGroupDataTest();
cid=1
pid=1

敏捷执行查询 >> 子任务6,101
瀑布执行查询 >> 更多任务93,131
看板执行查询 >> 开发任务71,161
敏捷执行查询统计 >> 11
瀑布执行查询统计 >> 4
看板执行查询统计 >> 4

*/

$executionIDList = array('101', '131', '161');
$count           = array('0', '1');

$execution = new executionTest();
var_dump($execution->getKanbanGroupDataTest($executionIDList[0], $count[0]));die;
r($execution->getKanbanGroupDataTest($executionIDList[0], $count[0])) && p('906:name,execution') && e('子任务6,101');    // 敏捷执行查询
r($execution->getKanbanGroupDataTest($executionIDList[1], $count[0])) && p('693:name,execution') && e('更多任务93,131'); // 瀑布执行查询
r($execution->getKanbanGroupDataTest($executionIDList[2], $count[0])) && p('61:name,execution')  && e('开发任务71,161'); // 看板执行查询
r($execution->getKanbanGroupDataTest($executionIDList[0], $count[1])) && p()                     && e('11');             // 敏捷执行查询统计
r($execution->getKanbanGroupDataTest($executionIDList[1], $count[1])) && p()                     && e('4');              // 瀑布执行查询统计
r($execution->getKanbanGroupDataTest($executionIDList[2], $count[1])) && p()                     && e('4');              // 看板执行查询统计