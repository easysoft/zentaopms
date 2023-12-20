#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanspace')->gen(3);

/**

title=测试 kanbanModel->closeSpace();
timeout=0
cid=1

- 查看关闭前的字段
 - 属性status @active
 - 属性closedBy @~~
 - 属性closedDate @~~
- 查看关闭前的字段
 - 属性status @active
 - 属性closedBy @~~
 - 属性closedDate @~~
- 查看关闭前的字段
 - 属性status @active
 - 属性closedBy @~~
 - 属性closedDate @~~
- 查看关闭后的字段
 - 属性status @closed
 - 属性closedBy @admin
 - 属性closedDate @2023-01-01 00:00:00
- 查看关闭后的字段
 - 属性status @closed
 - 属性closedBy @user10
 - 属性closedDate @2023-01-02 00:00:00
- 查看关闭后的字段
 - 属性status @closed
 - 属性closedBy @admin
 - 属性closedDate @2023-01-01 00:00:00

*/

global $tester;
$tester->loadModel('kanban');

$space1 = $tester->kanban->getSpaceById(1);
$space2 = $tester->kanban->getSpaceById(2);
$space3 = $tester->kanban->getSpaceById(3);

r($space1) && p('status,closedBy,closedDate') && e('active,~~,~~'); // 查看关闭前的字段
r($space2) && p('status,closedBy,closedDate') && e('active,~~,~~'); // 查看关闭前的字段
r($space3) && p('status,closedBy,closedDate') && e('active,~~,~~'); // 查看关闭前的字段

$param1 = new stdclass();
$param1->status     = 'closed';
$param1->closedBy   = 'admin';
$param1->closedDate = '2023-01-01';

$param2 = new stdclass();
$param2->status     = 'closed';
$param2->closedBy   = 'user10';
$param2->closedDate = '2023-01-02';

$param3 = new stdclass();
$param3->status     = 'closed';
$param3->closedBy   = 'admin';
$param3->closedDate = '2023-01-01';

$tester->kanban->closeSpace(1, $param1);
$tester->kanban->closeSpace(2, $param2);
$tester->kanban->closeSpace(3, $param3);

$space1 = $tester->kanban->getSpaceById(1);
$space2 = $tester->kanban->getSpaceById(2);
$space3 = $tester->kanban->getSpaceById(3);

r($space1) && p('status,closedBy,closedDate') && e('closed,admin,2023-01-01 00:00:00');  // 查看关闭后的字段
r($space2) && p('status,closedBy,closedDate') && e('closed,user10,2023-01-02 00:00:00'); // 查看关闭后的字段
r($space3) && p('status,closedBy,closedDate') && e('closed,admin,2023-01-01 00:00:00');  // 查看关闭后的字段