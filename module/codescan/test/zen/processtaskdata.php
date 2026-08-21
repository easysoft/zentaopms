#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->processTaskData();
timeout=0
cid=0

- 校验runTime字段 >> 2m5s
- 测试空task返回对象 >> 1
- 测试带repoList返回对象 >> 1
- 测试空task空repoList返回对象 >> 1
- 测试有效task返回对象 >> 1

*/

su('admin');
$test = new codescanZenTest();

$task = new stdclass();
$task->repoID = 100;
$task->cost = 125;
$task->started = 1700000000000;
$task->finished = 1700000125000;
$task->repoNumber = 5;
$emptyTask = (object)array('repoID' => 0, 'repoNumber' => 0);
r($test->processTaskDataTest($task)) && p('runTime') && e('2m5s');
r(is_object($test->processTaskDataTest($emptyTask))) && p() && e('1');
r(is_object($test->processTaskDataTest($task, array(100 => 'my-repo')))) && p() && e('1');
r(is_object($test->processTaskDataTest($emptyTask, array()))) && p() && e('1');
r(is_object($test->processTaskDataTest($task))) && p() && e('1');
