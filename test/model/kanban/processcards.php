#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->processCards();
cid=1
pid=1

计算删除列203后的卡片 >> 204:,407,408,807,405,406,805,
计算删除列204后的卡片 >> 203:,405,406,805,407,408,807,405,406,805,
计算删除列207后的卡片 >> 208:,415,416,815,413,414,813,
计算删除列208后的卡片 >> 207:,413,414,813,415,416,815,413,414,813,

*/

$columnIDList = array('203', '204', '207', '208');

$kanban = new kanbanTest();

r($kanban->processCardsTest($columnIDList[0])) && p() && e('204:,407,408,807,405,406,805,');             // 计算删除列203后的卡片
r($kanban->processCardsTest($columnIDList[1])) && p() && e('203:,405,406,805,407,408,807,405,406,805,'); // 计算删除列204后的卡片
r($kanban->processCardsTest($columnIDList[2])) && p() && e('208:,415,416,815,413,414,813,');             // 计算删除列207后的卡片
r($kanban->processCardsTest($columnIDList[3])) && p() && e('207:,413,414,813,415,416,815,413,414,813,'); // 计算删除列208后的卡片
