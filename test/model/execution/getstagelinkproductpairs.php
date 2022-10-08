#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getStageLinkProductPairsTest();
cid=1
pid=1

敏捷执行产品查询 >> 多平台产品91
瀑布执行产品查询 >> 已关闭的正常产品31
看板执行产品查询 >> 已关闭的多分支产品61
敏捷执行产品统计 >> 3
瀑布执行产品统计 >> 3
看板执行产品统计 >> 3

*/

$sprintIDList = array('101', '102', '103');
$stageIDList  = array('131', '132', '133');
$kanbanIDList = array('161', '162', '163');
$count        = array('0','1');

$execution = new executionTest();
r($execution->getStageLinkProductPairsTest($sprintIDList, $count[0])) && p('101')  && e('多平台产品91');         // 敏捷执行产品查询
r($execution->getStageLinkProductPairsTest($stageIDList, $count[0]))  && p('131')  && e('已关闭的正常产品31');   // 瀑布执行产品查询
r($execution->getStageLinkProductPairsTest($kanbanIDList, $count[0])) && p('161')  && e('已关闭的多分支产品61'); // 看板执行产品查询
r($execution->getStageLinkProductPairsTest($sprintIDList, $count[1])) && p()       && e('3');                    // 敏捷执行产品统计
r($execution->getStageLinkProductPairsTest($stageIDList, $count[1]))  && p()       && e('3');                    // 瀑布执行产品统计
r($execution->getStageLinkProductPairsTest($kanbanIDList, $count[1])) && p()       && e('3');                    // 看板执行产品统计