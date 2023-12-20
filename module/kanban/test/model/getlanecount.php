#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('kanbanregion')->gen(5);
zdTable('kanbanlane')->gen(20);

/**

title=测试 kanbanModel->getLaneCount();
timeout=0
cid=1

- 查看看板1的泳道数量 @1
- 查看看板2的泳道数量 @0
- 查看看板3的泳道数量 @0
- 查看看板4的泳道数量 @0
- 查看看板5的泳道数量 @0

*/
global $tester;
$tester->loadModel('kanban');

r($tester->kanban->getLaneCount(1))           && p('') && e('1'); // 查看看板1的泳道数量
r($tester->kanban->getLaneCount(2, 'kanban')) && p('') && e('0'); // 查看看板2的泳道数量
r($tester->kanban->getLaneCount(3, 'story'))  && p('') && e('0'); // 查看看板3的泳道数量
r($tester->kanban->getLaneCount(4, 'task'))   && p('') && e('0'); // 查看看板4的泳道数量
r($tester->kanban->getLaneCount(5, ''))       && p('') && e('0'); // 查看看板5的泳道数量