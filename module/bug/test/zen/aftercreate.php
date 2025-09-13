#!/usr/bin/env php
<?php

/**

title=测试 bugZen::afterCreate();
timeout=0
cid=0

- 执行bugTest模块的afterCreateTest方法，参数是$bug, array  @1
- 执行bugTest模块的afterCreateTest方法，参数是$bug, array  @1
- 执行bugTest模块的afterCreateTest方法，参数是$bug, array  @1
- 执行bugTest模块的afterCreateTest方法，参数是$bug, array  @1
- 执行bugTest模块的afterCreateTest方法，参数是$bug, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->gen(3);
zenData('todo')->gen(2);
zenData('user')->gen(5);
zenData('file')->gen(3);
zenData('kanbancolumn')->gen(3);
zenData('kanbanlane')->gen(3);
zenData('kanbancell')->gen(0);

su('admin');

$bugTest = new bugTest();

$bug = new stdclass();
$bug->id = 1;
$bug->module = 10;
$bug->execution = 101;

r($bugTest->afterCreateTest($bug, array(), '')) && p() && e('1');
r($bugTest->afterCreateTest($bug, array('laneID' => 1, 'columnID' => 2), 'kanban')) && p() && e('1');
r($bugTest->afterCreateTest($bug, array('todoID' => 1), '')) && p() && e('1');
r($bugTest->afterCreateTest($bug, array('fileList' => '[]'), '')) && p() && e('1');
r($bugTest->afterCreateTest($bug, array('laneID' => 0, 'columnID' => 0), '')) && p() && e('1');