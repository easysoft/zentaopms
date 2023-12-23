#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';

zdTable('kanban')->gen(2);
zdTable('kanbanregion')->gen(2);

/**

title=测试 kanbanModel->getRDRegionActions();
timeout=0
cid=1

- 查看admin可以获取到几个操作按钮 @3
- 查看admin可以获取到几个操作按钮 @3
- 查看user1可以获取到几个操作按钮 @0
- 查看user1可以获取到几个操作按钮 @0

*/
global $tester;
$tester->loadModel('kanban');

su('admin');
$actions1 = $tester->kanban->getRDRegionActions(1, 1);
$actions2 = $tester->kanban->getRDRegionActions(2, 2);
r(count($actions1[0]['items'])) && p() && e('3'); // 查看admin可以获取到几个操作按钮
r(count($actions2[0]['items'])) && p() && e('3'); // 查看admin可以获取到几个操作按钮

su('user1');
$actions3 = $tester->kanban->getRDRegionActions(1, 1);
$actions4 = $tester->kanban->getRDRegionActions(2, 2);
r(count($actions3[0]['items'])) && p() && e('0'); // 查看user1可以获取到几个操作按钮
r(count($actions4[0]['items'])) && p() && e('0'); // 查看user1可以获取到几个操作按钮