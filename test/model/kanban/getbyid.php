#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getByID();
cid=1
pid=1

测试查询看板1的信息 >> 通用看板1,1,po15,,user3,po15,,,user3,po15
测试查询看板2的信息 >> 通用看板2,1,po15,,user3,po15,,,user3,po15
测试查询看板3的信息 >> 通用看板3,2,po16,,user4,po16,,,user4,po16
测试查询看板4的信息 >> 通用看板4,2,po16,,user4,po16,,,user4,po16
测试查询看板5的信息 >> 通用看板5,3,po17,,user5,po17,,,user5,po17
测试查询不存在的看板信息 >> 0

*/

$kanbanIDList = array('1', '2', '3', '4', '5', '1000001');

$kanban = new kanbanTest();

r($kanban->getByIDTest($kanbanIDList[0])) && p('name,space,owner,team,whitelist') && e('通用看板1,1,po15,,user3,po15,,,user3,po15'); // 测试查询看板1的信息
r($kanban->getByIDTest($kanbanIDList[1])) && p('name,space,owner,team,whitelist') && e('通用看板2,1,po15,,user3,po15,,,user3,po15'); // 测试查询看板2的信息
r($kanban->getByIDTest($kanbanIDList[2])) && p('name,space,owner,team,whitelist') && e('通用看板3,2,po16,,user4,po16,,,user4,po16'); // 测试查询看板3的信息
r($kanban->getByIDTest($kanbanIDList[3])) && p('name,space,owner,team,whitelist') && e('通用看板4,2,po16,,user4,po16,,,user4,po16'); // 测试查询看板4的信息
r($kanban->getByIDTest($kanbanIDList[4])) && p('name,space,owner,team,whitelist') && e('通用看板5,3,po17,,user5,po17,,,user5,po17'); // 测试查询看板5的信息
r($kanban->getByIDTest($kanbanIDList[5])) && p('name,space,owner,team,whitelist') && e('0');                                         // 测试查询不存在的看板信息