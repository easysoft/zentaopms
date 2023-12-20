#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel->createLane();
timeout=0
cid=1

- 测试创建与其他泳道使用相同看板列的泳道
 - 属性name @测试创建与其他泳道使用相同看板列的泳道
 - 属性region @1
 - 属性group @1
- 测试创建使用独立看板列的泳道
 - 属性name @测试创建使用独立看板列的泳道
 - 属性region @1
 - 属性group @2
- 测试创建不填写名称的泳道第name条的0属性 @『泳道名称』不能为空。
- 测试创建复用泳道1信息的泳道
 - 属性name @测试创建与其他泳道使用相同看板列的泳道
 - 属性region @1
 - 属性group @1
- 测试创建复用泳道2信息的泳道
 - 属性name @测试创建使用独立看板列的泳道
 - 属性region @1
 - 属性group @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanban')->gen(1);
zdTable('kanbanregion')->gen(1);
zdTable('kanbanlane')->gen(1);
zdTable('kanbangroup')->gen(1);

$sameAsOtherLane = new stdclass();
$sameAsOtherLane->name      = '测试创建与其他泳道使用相同看板列的泳道';
$sameAsOtherLane->mode      = 'sameAsOther';
$sameAsOtherLane->otherLane = '1';
$sameAsOtherLane->region    = 1;
$sameAsOtherLane->color     = '#7ec5ff';

$independentLane = new stdclass();
$independentLane->name   = '测试创建使用独立看板列的泳道';
$independentLane->mode   = 'independent';
$independentLane->region = 1;
$independentLane->color  = '#229f24';

$emptyNameLane = new stdclass();
$emptyNameLane->name   = '';
$emptyNameLane->mode   = 'independent';
$emptyNameLane->region = 1;
$emptyNameLane->color  = '#229f24';

$lane4 = new stdclass();
$lane4->id = 1;

$lane5 = new stdclass();
$lane5->id = 2;

$kanban = new kanbanTest();

r($kanban->createLaneTest(1, 1, $sameAsOtherLane))         && p('name,region,group') && e('测试创建与其他泳道使用相同看板列的泳道,1,1'); //测试创建与其他泳道使用相同看板列的泳道
r($kanban->createLaneTest(1, 1, $independentLane))         && p('name,region,group') && e('测试创建使用独立看板列的泳道,1,2');           //测试创建使用独立看板列的泳道
r($kanban->createLaneTest(1, 1, $emptyNameLane))           && p('name:0')            && e('『泳道名称』不能为空。');                     //测试创建不填写名称的泳道
r($kanban->createLaneTest(1, 1, $sameAsOtherLane, 'copy')) && p('name,region,group') && e('测试创建与其他泳道使用相同看板列的泳道,1,1'); //测试创建复用泳道1信息的泳道
r($kanban->createLaneTest(1, 1, $independentLane, 'copy')) && p('name,region,group') && e('测试创建使用独立看板列的泳道,1,2');           //测试创建复用泳道2信息的泳道