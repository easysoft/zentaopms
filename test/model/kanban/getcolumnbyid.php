#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getColumnByID();
cid=1
pid=1

测试查询看板列1的信息 >> column1,1,未开始,-1
测试查询看板列2的信息 >> column2,1,进行中,-1
测试查询看板列3的信息 >> column3,1,已完成,-1
测试查询看板列4的信息 >> column4,1,已关闭,-1
测试查询看板列5的信息 >> column5,2,未开始,-1
测试查询不存在看板列的信息 >> 0

*/

$columnIDList = array('1', '2', '3', '4', '5', '1000001');

$kanban = new kanbanTest();

r($kanban->getColumnByIDTest($columnIDList[0])) && p('type,region,name,limit') && e('column1,1,未开始,-1'); // 测试查询看板列1的信息
r($kanban->getColumnByIDTest($columnIDList[1])) && p('type,region,name,limit') && e('column2,1,进行中,-1'); // 测试查询看板列2的信息
r($kanban->getColumnByIDTest($columnIDList[2])) && p('type,region,name,limit') && e('column3,1,已完成,-1'); // 测试查询看板列3的信息
r($kanban->getColumnByIDTest($columnIDList[3])) && p('type,region,name,limit') && e('column4,1,已关闭,-1'); // 测试查询看板列4的信息
r($kanban->getColumnByIDTest($columnIDList[4])) && p('type,region,name,limit') && e('column5,2,未开始,-1'); // 测试查询看板列5的信息
r($kanban->getColumnByIDTest($columnIDList[5])) && p('type,region,name,limit') && e('0');                    // 测试查询不存在看板列的信息