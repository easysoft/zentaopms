#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('task')->loadYaml('task')->gen(30);

/**

title=taskModel->getDataOfFinishedTasksPerDay();
timeout=0
cid=18793

- 每日完成的数量 @30
- 统计2021-01-30完成的任务数量
 - 第2021-01-30条的name属性 @2021-01-30
 - 第2021-01-30条的value属性 @1
- 统计2021-01-29完成的任务数量
 - 第2021-01-29条的name属性 @2021-01-29
 - 第2021-01-29条的value属性 @1
- 统计2021-01-28完成的任务数量
 - 第2021-01-28条的name属性 @2021-01-28
 - 第2021-01-28条的value属性 @1
- 统计2021-01-27完成的任务数量
 - 第2021-01-27条的name属性 @2021-01-27
 - 第2021-01-27条的value属性 @1

*/

global $tester;
$taskModule = $tester->loadModel('task');

$result = $taskModule->getDataOfFinishedTasksPerDay();
r(count($result)) && p()                        && e('30');           // 每日完成的数量
r($result)        && p('2021-01-30:name,value') && e('2021-01-30,1'); // 统计2021-01-30完成的任务数量
r($result)        && p('2021-01-29:name,value') && e('2021-01-29,1'); // 统计2021-01-29完成的任务数量
r($result)        && p('2021-01-28:name,value') && e('2021-01-28,1'); // 统计2021-01-28完成的任务数量
r($result)        && p('2021-01-27:name,value') && e('2021-01-27,1'); // 统计2021-01-27完成的任务数量