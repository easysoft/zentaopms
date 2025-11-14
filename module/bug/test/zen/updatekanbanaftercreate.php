#!/usr/bin/env php
<?php

/**

title=测试 bugZen::updateKanbanAfterCreate();
timeout=0
cid=15482

- 执行bugTest模块的updateKanbanAfterCreateTest方法，参数是$bug1, 0, 0, ''  @1
- 执行bugTest模块的updateKanbanAfterCreateTest方法，参数是$bug2, 1, 1, ''  @1
- 执行bugTest模块的updateKanbanAfterCreateTest方法，参数是$bug3, 0, 1, ''  @1
- 执行bugTest模块的updateKanbanAfterCreateTest方法，参数是$bug4, 1, 0, ''  @1
- 执行bugTest模块的updateKanbanAfterCreateTest方法，参数是$bug5, 0, 0, ''  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('bug')->gen(0);
zenData('project')->gen(5);
zenData('kanban')->gen(3);
zenData('kanbanlane')->gen(5);
zenData('kanbancolumn')->gen(5);
zenData('kanbancell')->gen(10);

su('admin');

$bugTest = new bugZenTest();

$bug1 = new stdClass();
$bug1->id = 1;
$bug1->execution = 0;
$bug1->title = 'Test Bug 1';

$bug2 = new stdClass();
$bug2->id = 2;
$bug2->execution = 1;
$bug2->title = 'Test Bug 2';

$bug3 = new stdClass();
$bug3->id = 3;
$bug3->execution = 1;
$bug3->title = 'Test Bug 3';

$bug4 = new stdClass();
$bug4->id = 4;
$bug4->execution = 2;
$bug4->title = 'Test Bug 4';

$bug5 = new stdClass();
$bug5->id = 5;
$bug5->execution = 2;
$bug5->title = 'Test Bug 5';

r($bugTest->updateKanbanAfterCreateTest($bug1, 0, 0, '')) && p() && e('1');
r($bugTest->updateKanbanAfterCreateTest($bug2, 1, 1, '')) && p() && e('1');
r($bugTest->updateKanbanAfterCreateTest($bug3, 0, 1, '')) && p() && e('1');
r($bugTest->updateKanbanAfterCreateTest($bug4, 1, 0, '')) && p() && e('1');
r($bugTest->updateKanbanAfterCreateTest($bug5, 0, 0, '')) && p() && e('1');