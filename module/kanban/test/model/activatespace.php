#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanspace')->gen(3);

/**

title=测试 kanbanModel->activateSpace();
timeout=0
cid=1

- 查看激活前的字段
 - 属性status @active
 - 属性activatedBy @~~
 - 属性activatedDate @~~
- 查看激活前的字段
 - 属性status @active
 - 属性activatedBy @~~
 - 属性activatedDate @~~
- 查看激活前的字段
 - 属性status @active
 - 属性activatedBy @~~
 - 属性activatedDate @~~
- 查看激活后的字段
 - 属性status @active
 - 属性activatedBy @admin
 - 属性activatedDate @2023-01-01 00:00:00
- 查看激活后的字段
 - 属性status @active
 - 属性activatedBy @user10
 - 属性activatedDate @2023-01-02 00:00:00
- 查看激活后的字段
 - 属性status @active
 - 属性activatedBy @admin
 - 属性activatedDate @2023-01-01 00:00:00

*/

global $tester;
$tester->loadModel('kanban');

$space1 = $tester->kanban->getSpaceById(1);
$space2 = $tester->kanban->getSpaceById(2);
$space3 = $tester->kanban->getSpaceById(3);

r($space1) && p('status,activatedBy,activatedDate') && e('active,~~,~~'); // 查看激活前的字段
r($space2) && p('status,activatedBy,activatedDate') && e('active,~~,~~'); // 查看激活前的字段
r($space3) && p('status,activatedBy,activatedDate') && e('active,~~,~~'); // 查看激活前的字段

$param1 = new stdclass();
$param1->status        = 'active';
$param1->activatedBy   = 'admin';
$param1->activatedDate = '2023-01-01';

$param2 = new stdclass();
$param2->status        = 'active';
$param2->activatedBy   = 'user10';
$param2->activatedDate = '2023-01-02';

$param3 = new stdclass();
$param3->status        = 'active';
$param3->activatedBy   = 'admin';
$param3->activatedDate = '2023-01-01';

$tester->kanban->activateSpace(1, $param1);
$tester->kanban->activateSpace(2, $param2);
$tester->kanban->activateSpace(3, $param3);

$space1 = $tester->kanban->getSpaceById(1);
$space2 = $tester->kanban->getSpaceById(2);
$space3 = $tester->kanban->getSpaceById(3);

r($space1) && p('status,activatedBy,activatedDate') && e('active,admin,2023-01-01 00:00:00');  // 查看激活后的字段
r($space2) && p('status,activatedBy,activatedDate') && e('active,user10,2023-01-02 00:00:00'); // 查看激活后的字段
r($space3) && p('status,activatedBy,activatedDate') && e('active,admin,2023-01-01 00:00:00');  // 查看激活后的字段
