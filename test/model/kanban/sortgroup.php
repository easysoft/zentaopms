#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->sortGroup();
cid=1
pid=1

测试区域101 泳道组101 102 103排序 >> 103,101,102
测试区域102 泳道组104 105 106排序 >> 105,106,104
测试区域103 泳道组107 108 109排序 >> 107,109,108
测试区域104 泳道组110 111 112排序 >> 112,111,110
测试区域105 泳道组113 114 115排序 >> 114,113,115

*/

$regionIDList = array('101', '102', '103', '104', '105');
$groupIDList  = array(array('103', '101', '102'), array('105', '106', '104'), array('107', '109', '108'), array('112', '111', '110'), array('114', '113', '115'));

$kanban = new kanbanTest();

r($kanban->sortGroupTest($regionIDList[0], $groupIDList[0])) && p() && e('103,101,102'); // 测试区域101 泳道组101 102 103排序
r($kanban->sortGroupTest($regionIDList[1], $groupIDList[1])) && p() && e('105,106,104'); // 测试区域102 泳道组104 105 106排序
r($kanban->sortGroupTest($regionIDList[2], $groupIDList[2])) && p() && e('107,109,108'); // 测试区域103 泳道组107 108 109排序
r($kanban->sortGroupTest($regionIDList[3], $groupIDList[3])) && p() && e('112,111,110'); // 测试区域104 泳道组110 111 112排序
r($kanban->sortGroupTest($regionIDList[4], $groupIDList[4])) && p() && e('114,113,115'); // 测试区域105 泳道组113 114 115排序