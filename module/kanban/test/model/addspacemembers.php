#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanspace')->gen(2);

/**

title=测试 kanbanModel->addSpaceMembers();
timeout=0
cid=1

- 查看添加成员后的看板空间1字段
 - 属性whitelist @,user3,po15,,admin,po1,dev1,qa1
 - 属性team @,user3,po15,,admin,po1,dev1,qa1
- 查看添加成员后的看板空间2字段
 - 属性whitelist @,user4,po16,,admin,po1,dev1,qa1
 - 属性team @,user4,po16,,admin,po1,dev1,qa1

*/

global $tester;
$tester->loadModel('kanban');
$members = array('admin', 'po1', 'dev1', 'qa1');

$tester->kanban->addSpaceMembers(1, 'whitelist', $members);
$tester->kanban->addSpaceMembers(1, 'team', $members);
$tester->kanban->addSpaceMembers(2, 'whitelist', $members);
$tester->kanban->addSpaceMembers(2, 'team', $members);

$space1 = $tester->kanban->getSpaceById(1);
$space2 = $tester->kanban->getSpaceById(2);

r($space1) && p('whitelist|team', '|') && e(',user3,po15,,admin,po1,dev1,qa1|,user3,po15,,admin,po1,dev1,qa1'); // 查看添加成员后的看板空间1字段
r($space2) && p('whitelist|team', '|') && e(',user4,po16,,admin,po1,dev1,qa1|,user4,po16,,admin,po1,dev1,qa1'); // 查看添加成员后的看板空间2字段