#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getLanePairsByGroup();
cid=1
pid=1

获取泳道组101的泳道 >> 研发需求
获取泳道组102的泳道 >> Bug
获取泳道组103的泳道 >> 任务
获取泳道组104的泳道 >> 研发需求
获取泳道组105的泳道 >> Bug
获取不存在泳道组的泳道 >> 0

*/

$groupIDList = array('101', '102', '103', '104', '105', '1000001');

$kanban = new kanbanTest();

r($kanban->getLanePairsByGroupTest($groupIDList[0])) && p() && e('研发需求'); // 获取泳道组101的泳道
r($kanban->getLanePairsByGroupTest($groupIDList[1])) && p() && e('Bug'); // 获取泳道组102的泳道
r($kanban->getLanePairsByGroupTest($groupIDList[2])) && p() && e('任务'); // 获取泳道组103的泳道
r($kanban->getLanePairsByGroupTest($groupIDList[3])) && p() && e('研发需求'); // 获取泳道组104的泳道
r($kanban->getLanePairsByGroupTest($groupIDList[4])) && p() && e('Bug'); // 获取泳道组105的泳道
r($kanban->getLanePairsByGroupTest($groupIDList[5])) && p() && e('0'); // 获取不存在泳道组的泳道