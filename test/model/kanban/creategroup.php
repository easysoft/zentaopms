#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->createGroup();
cid=1
pid=1

测试创建看板1 区域1的看板组 >> ,1,
测试创建看板2 区域2的看板组 >> ,2,
测试创建看板3 区域3的看板组 >> ,3,
测试创建看板4 区域4的看板组 >> ,4,
测试创建看板5 区域5的看板组 >> ,5,

*/

$kanbanIDList = array('1', '2', '3', '4', '5');
$regionIDList = array('1', '2', '3', '4', '5');

$kanban = new kanbanTest();

r($kanban->createGroupTest($kanbanIDList[0], $regionIDList[0])) && p('kanaban,region,orde') && e(',1,'); // 测试创建看板1 区域1的看板组
r($kanban->createGroupTest($kanbanIDList[1], $regionIDList[1])) && p('kanaban,region,orde') && e(',2,'); // 测试创建看板2 区域2的看板组
r($kanban->createGroupTest($kanbanIDList[2], $regionIDList[2])) && p('kanaban,region,orde') && e(',3,'); // 测试创建看板3 区域3的看板组
r($kanban->createGroupTest($kanbanIDList[3], $regionIDList[3])) && p('kanaban,region,orde') && e(',4,'); // 测试创建看板4 区域4的看板组
r($kanban->createGroupTest($kanbanIDList[4], $regionIDList[4])) && p('kanaban,region,orde') && e(',5,'); // 测试创建看板5 区域5的看板组
