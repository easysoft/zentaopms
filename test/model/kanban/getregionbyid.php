#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getRegionByID();
cid=1
pid=1

测试查询看板1信息 >> 默认区域,1,1
测试查询看板2信息 >> 默认区域,2,1
测试查询看板3信息 >> 默认区域,3,2
测试查询看板4信息 >> 默认区域,4,2
测试查询看板5信息 >> 默认区域,5,3
测试查询不存在的看板信息 >> 0

*/
$regionIDList = array('1', '2', '3', '4', '5', '10001');

$kanban = new kanbanTest();

r($kanban->getRegionByIDTest($regionIDList[0])) && p('name,kanban,space') && e('默认区域,1,1'); // 测试查询看板1信息
r($kanban->getRegionByIDTest($regionIDList[1])) && p('name,kanban,space') && e('默认区域,2,1'); // 测试查询看板2信息
r($kanban->getRegionByIDTest($regionIDList[2])) && p('name,kanban,space') && e('默认区域,3,2'); // 测试查询看板3信息
r($kanban->getRegionByIDTest($regionIDList[3])) && p('name,kanban,space') && e('默认区域,4,2'); // 测试查询看板4信息
r($kanban->getRegionByIDTest($regionIDList[4])) && p('name,kanban,space') && e('默认区域,5,3'); // 测试查询看板5信息
r($kanban->getRegionByIDTest($regionIDList[5])) && p('name,kanban,space') && e('0'); // 测试查询不存在的看板信息