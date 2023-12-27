#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancolumn')->gen(10);

/**

title=测试 kanbanModel->getColumnPairsByGroup();
timeout=0
cid=1

- 查看分组1的看板列数量 @4
- 查看分组2的看板列数量 @4
- 查看分组3的看板列数量 @2
- 查看分组4的看板列数量 @0
- 查看分组5的看板列数量 @0

*/

global $tester;
$tester->loadModel('kanban');

r(count($tester->kanban->getColumnPairsByGroup(1))) && p() && e('4'); // 查看分组1的看板列数量
r(count($tester->kanban->getColumnPairsByGroup(2))) && p() && e('4'); // 查看分组2的看板列数量
r(count($tester->kanban->getColumnPairsByGroup(3))) && p() && e('2'); // 查看分组3的看板列数量
r(count($tester->kanban->getColumnPairsByGroup(4))) && p() && e('0'); // 查看分组4的看板列数量
r(count($tester->kanban->getColumnPairsByGroup(5))) && p() && e('0'); // 查看分组5的看板列数量