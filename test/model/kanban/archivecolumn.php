#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->archiveColumn();
cid=1
pid=1

测试归档列1 >> 1,1
测试归档列2 >> 2,1
测试归档列3 >> 3,1
测试归档列4 >> 4,1
测试归档列5 >> 5,1
测试归档不存在的列 >> 0

*/

$columnIDList = array('1', '2', '3', '4', '5', '1000001');

$kanban = new kanbanTest();

r($kanban->archiveColumnTest($columnIDList[0])) && p('id,archived') && e('1,1'); // 测试归档列1
r($kanban->archiveColumnTest($columnIDList[1])) && p('id,archived') && e('2,1'); // 测试归档列2
r($kanban->archiveColumnTest($columnIDList[2])) && p('id,archived') && e('3,1'); // 测试归档列3
r($kanban->archiveColumnTest($columnIDList[3])) && p('id,archived') && e('4,1'); // 测试归档列4
r($kanban->archiveColumnTest($columnIDList[4])) && p('id,archived') && e('5,1'); // 测试归档列5
r($kanban->archiveColumnTest($columnIDList[5])) && p()              && e('0');   // 测试归档不存在的列
