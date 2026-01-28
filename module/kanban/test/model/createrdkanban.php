#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(105);
zenData('kanbanregion')->gen(0);
zenData('kanbancolumn')->gen(0);
zenData('kanbanlane')->gen(0);

/**

title=测试 kanbanModel->createRDKanban();
timeout=0
cid=16897

- 测试创建执行101的执行看板 @1,4,38

- 测试创建执行102的执行看板 @1,4,38

- 测试创建执行103的执行看板 @1,4,38

- 测试创建执行104的执行看板 @1,4,38

- 测试创建执行105的执行看板 @1,4,38

*/

$execution1 = new stdclass();
$execution1->id = '101';

$execution2 = new stdclass();
$execution2->id = '102';

$execution3 = new stdclass();
$execution3->id = '103';

$execution4 = new stdclass();
$execution4->id = '104';

$execution5 = new stdclass();
$execution5->id = '105';

$kanban = new kanbanModelTest();

r($kanban->createRDKanbanTest($execution1)) && p() && e('1,4,38'); // 测试创建执行101的执行看板
r($kanban->createRDKanbanTest($execution2)) && p() && e('1,4,38'); // 测试创建执行102的执行看板
r($kanban->createRDKanbanTest($execution3)) && p() && e('1,4,38'); // 测试创建执行103的执行看板
r($kanban->createRDKanbanTest($execution4)) && p() && e('1,4,38'); // 测试创建执行104的执行看板
r($kanban->createRDKanbanTest($execution5)) && p() && e('1,4,38'); // 测试创建执行105的执行看板