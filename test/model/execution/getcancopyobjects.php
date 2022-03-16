#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getCanCopyObjectsTest();
cid=1
pid=1

敏捷执行关联用例 >> 101,1,1
瀑布执行关联用例 >> 131,43,169
看板执行关联用例 >> 161,68,269
敏捷执行关联用例统计 >> 4
瀑布执行关联用例统计 >> 4
看板执行关联用例统计 >> 4

*/

$projectIDList = array('11', '45', '71');
$count         = array('0','1');

$execution = new executionTest();
r($execution->getCanCopyObjectsTest($projectIDList[0], $count[0])) && p('101') && e('迭代1');  // 敏捷项目数据查询
r($execution->getCanCopyObjectsTest($projectIDList[1], $count[0])) && p('135') && e('阶段35'); // 瀑布项目数据查询
r($execution->getCanCopyObjectsTest($projectIDList[2], $count[0])) && p('161') && e('看板61'); // 看板项目数据查询
r($execution->getCanCopyObjectsTest($projectIDList[0], $count[1])) && p()      && e('8');      // 敏捷项目数据统计
r($execution->getCanCopyObjectsTest($projectIDList[1], $count[1])) && p()      && e('9');      // 瀑布项目数据统计
r($execution->getCanCopyObjectsTest($projectIDList[2], $count[1])) && p()      && e('7');      // 看板项目数据统计
