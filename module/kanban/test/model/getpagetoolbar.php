#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('kanban')->gen(2);

/**

title=测试 kanbanModel->getPageToolBar();
timeout=0
cid=1

- 查看普通用户获取操作按钮的长度 @161
- 查看管理员获取操作按钮的长度 @1247

*/
global $tester;
$tester->loadModel('kanban');

$kanban1 = $tester->kanban->getByID(1);
$kanban2 = $tester->kanban->getByID(2);

$toolbar1 = $tester->kanban->getPageToolBar($kanban1);
su('admin');
$toolbar2 = $tester->kanban->getPageToolBar($kanban2);

r(strlen($toolbar1)) && p('') && e('161');  // 查看普通用户获取操作按钮的长度
r(strlen($toolbar2)) && p('') && e('1247'); // 查看管理员获取操作按钮的长度