#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanban')->gen(5);

/**

title=测试 kanbanModel->getByID();
timeout=0
cid=1

- 测试查询看板1的信息
 - 属性name @通用看板1
 - 属性space @1
 - 属性owner @po15
 - 属性team @,user3,po15,
 - 属性whitelist @,user3,po15
- 测试查询看板2的信息
 - 属性name @通用看板2
 - 属性space @1
 - 属性owner @po15
 - 属性team @,user3,po15,
 - 属性whitelist @,user3,po15
- 测试查询看板3的信息
 - 属性name @通用看板3
 - 属性space @2
 - 属性owner @po16
 - 属性team @,user4,po16,
 - 属性whitelist @,user4,po16
- 测试查询看板4的信息
 - 属性name @通用看板4
 - 属性space @2
 - 属性owner @po16
 - 属性team @,user4,po16,
 - 属性whitelist @,user4,po16
- 测试查询看板5的信息
 - 属性name @通用看板5
 - 属性space @3
 - 属性owner @po17
 - 属性team @,user5,po17,
 - 属性whitelist @,user5,po17

*/

$kanbanIDList = array('1', '2', '3', '4', '5');

$kanban = new kanbanTest();

r($kanban->getByIDTest($kanbanIDList[0])) && p('name|space|owner|team|whitelist', '|') && e('通用看板1|1|po15|,user3,po15,|,user3,po15'); // 测试查询看板1的信息
r($kanban->getByIDTest($kanbanIDList[1])) && p('name|space|owner|team|whitelist', '|') && e('通用看板2|1|po15|,user3,po15,|,user3,po15'); // 测试查询看板2的信息
r($kanban->getByIDTest($kanbanIDList[2])) && p('name|space|owner|team|whitelist', '|') && e('通用看板3|2|po16|,user4,po16,|,user4,po16'); // 测试查询看板3的信息
r($kanban->getByIDTest($kanbanIDList[3])) && p('name|space|owner|team|whitelist', '|') && e('通用看板4|2|po16|,user4,po16,|,user4,po16'); // 测试查询看板4的信息
r($kanban->getByIDTest($kanbanIDList[4])) && p('name|space|owner|team|whitelist', '|') && e('通用看板5|3|po17|,user5,po17,|,user5,po17'); // 测试查询看板5的信息