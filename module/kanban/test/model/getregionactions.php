#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';

zdTable('kanban')->gen(2);
zdTable('kanbanregion')->gen(2);

/**

title=测试 kanbanModel->getRegionActions();
timeout=0
cid=1

- 查看admin可以获取到几个操作按钮 @8
- 查看admin可以获取到几个操作按钮 @8
- 查看user1可以获取到几个操作按钮 @1
- 查看user1可以获取到几个操作按钮 @1

*/
global $tester;
$tester->loadModel('kanban');

su('admin');
$actions1 = $tester->kanban->getRegionActions(1, 1, 2);
$actions2 = $tester->kanban->getRegionActions(2, 2, 2);
r(count($actions1[0]['items'])) && p() && e('8'); // 查看admin可以获取到几个操作按钮
r(count($actions2[0]['items'])) && p() && e('8'); // 查看admin可以获取到几个操作按钮

su('user1');
$actions3 = $tester->kanban->getRegionActions(1, 1, 2);
$actions4 = $tester->kanban->getRegionActions(2, 2, 2);
r(count($actions3[0]['items'])) && p() && e('1'); // 查看user1可以获取到几个操作按钮
r(count($actions4[0]['items'])) && p() && e('1'); // 查看user1可以获取到几个操作按钮