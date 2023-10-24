#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->setLaneHeight();
cid=1
pid=1

测试设置看板1的泳道高度 >> 通用看板1,0
测试设置看板1的泳道高度 >> 迭代1,3
测试设置看板1的泳道高度 >> 迭代2,4
测试设置看板1的泳道高度 >> 卡片数量必须是大于2的正整数。
测试设置看板1的泳道高度 >> 卡片数量必须是大于2的正整数。

*/

$laneIDList = array('1' ,'101', '102', '4', '5');
$heightTypeList = array('auto', 'custom');
$displayCardsList = array('', '3', '4', '2', 'a3');

$kanban = new kanbanTest();

r($kanban->setLaneHeightTest($laneIDList[0], $heightTypeList[0], $displayCardsList[0]))              && p('name,displayCards') && e('通用看板1,0');                    // 测试设置看板1的泳道高度
r($kanban->setLaneHeightTest($laneIDList[1], $heightTypeList[1], $displayCardsList[1], 'execution')) && p('name,displayCards') && e('迭代1,3');                        // 测试设置看板1的泳道高度
r($kanban->setLaneHeightTest($laneIDList[2], $heightTypeList[1], $displayCardsList[2], 'execution')) && p('name,displayCards') && e('迭代2,4');                        // 测试设置看板1的泳道高度
r($kanban->setLaneHeightTest($laneIDList[3], $heightTypeList[1], $displayCardsList[3]))              && p('displayCards')       && e('卡片数量必须是大于2的正整数。'); // 测试设置看板1的泳道高度
r($kanban->setLaneHeightTest($laneIDList[4], $heightTypeList[1], $displayCardsList[4]))              && p('displayCards')       && e('卡片数量必须是大于2的正整数。'); // 测试设置看板1的泳道高度
