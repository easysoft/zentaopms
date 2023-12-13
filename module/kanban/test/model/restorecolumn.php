#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancolumn')->gen(5);

/**

title=测试 kanbanModel->restoreColumn();
timeout=0
cid=1

- 还原看板列1
 - 属性name @未开始
 - 属性archived @0
- 还原看板列1
 - 属性name @进行中
 - 属性archived @0
- 还原看板列2
 - 属性name @已完成
 - 属性archived @0
- 还原看板列3
 - 属性name @已关闭
 - 属性archived @0
- 还原看板列4
 - 属性name @未开始
 - 属性archived @0

*/

$columnIDList = array('1', '2', '3', '4', '5');

$kanban = new kanbanTest();

$kanban->archiveColumnTest($columnIDList[0]);
$kanban->archiveColumnTest($columnIDList[1]);
$kanban->archiveColumnTest($columnIDList[2]);
$kanban->archiveColumnTest($columnIDList[3]);
$kanban->archiveColumnTest($columnIDList[4]);

r($kanban->restoreColumnTest($columnIDList[0])) && p('name,archived') && e('未开始,0'); // 还原看板列1
r($kanban->restoreColumnTest($columnIDList[1])) && p('name,archived') && e('进行中,0'); // 还原看板列1
r($kanban->restoreColumnTest($columnIDList[2])) && p('name,archived') && e('已完成,0'); // 还原看板列2
r($kanban->restoreColumnTest($columnIDList[3])) && p('name,archived') && e('已关闭,0'); // 还原看板列3
r($kanban->restoreColumnTest($columnIDList[4])) && p('name,archived') && e('未开始,0'); // 还原看板列4