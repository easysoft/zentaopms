#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->createLane();
cid=1
pid=1

测试创建与其他泳道使用相同看板列的泳道 >> 测试创建与其他泳道使用相同看板列的泳道,1,1
测试创建使用独立看板列的泳道 >> 测试创建使用独立看板列的泳道,1,641
测试创建不填写名称的泳道 >> 『泳道名称』不能为空。
测试创建复用泳道1信息的泳道 >> 默认泳道,1,1
测试创建复用泳道2信息的泳道 >> 默认泳道,2,2

*/

$kanbanID = 1;
$regionID = 1;

$lane1 = new stdclass();
$lane1->name      = '测试创建与其他泳道使用相同看板列的泳道';
$lane1->mode      = 'sameAsOther';
$lane1->otherLane = '1';
$lane1->color     = '#7ec5ff';

$lane2 = new stdclass();
$lane2->name  = '测试创建使用独立看板列的泳道';
$lane2->mode  = 'independent';
$lane2->color = '#229f24';

$lane3 = new stdclass();
$lane3->name  = '';
$lane3->mode  = 'independent';
$lane3->color = '#229f24';

$lane4 = new stdclass();
$lane4->id = 1;

$lane5 = new stdclass();
$lane5->id = 2;

$kanban = new kanbanTest();

r($kanban->createLaneTest($lane1, $kanbanID, $regionID)) && p('name,region,group') && e('测试创建与其他泳道使用相同看板列的泳道,1,1'); //测试创建与其他泳道使用相同看板列的泳道
r($kanban->createLaneTest($lane2, $kanbanID, $regionID)) && p('name,region,group') && e('测试创建使用独立看板列的泳道,1,641');         //测试创建使用独立看板列的泳道
r($kanban->createLaneTest($lane3, $kanbanID, $regionID)) && p('name:0')            && e('『泳道名称』不能为空。');                     //测试创建不填写名称的泳道
r($kanban->createLaneTest($lane4, $kanbanID, $regionID)) && p('name,region,group') && e('默认泳道,1,1');                               //测试创建复用泳道1信息的泳道
r($kanban->createLaneTest($lane5, $kanbanID, $regionID)) && p('name,region,group') && e('默认泳道,2,2');                               //测试创建复用泳道2信息的泳道
