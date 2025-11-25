#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::isCreateTask();
timeout=0
cid=17752

- 执行programplan模块的isCreateTaskTest方法，参数是-1  @1
- 执行programplan模块的isCreateTaskTest方法  @1
- 执行programplan模块的isCreateTaskTest方法，参数是1  @0
- 执行programplan模块的isCreateTaskTest方法，参数是6  @1
- 执行programplan模块的isCreateTaskTest方法，参数是999  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

zenData('project')->loadYaml('project')->gen(5);
zenData('project')->loadYaml('stage')->gen(5, $isClear = false);

$task = zenData('task');
$task->execution->range('1-5');
$task->deleted->range('0{8},1{2}');
$task->gen(10);

su('admin');

$programplan = new programplanTest();
r($programplan->isCreateTaskTest(-1)) && p() && e('1');
r($programplan->isCreateTaskTest(0)) && p() && e('1');
r($programplan->isCreateTaskTest(1)) && p() && e('0');
r($programplan->isCreateTaskTest(6)) && p() && e('1');
r($programplan->isCreateTaskTest(999)) && p() && e('1');