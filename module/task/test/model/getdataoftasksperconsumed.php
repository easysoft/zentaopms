#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('task')->loadYaml('task')->gen(30);

/**

title=taskModel->getDataOfTasksPerConsumed();
timeout=0
cid=18796

- 按消耗时间统计的数量 @3
- 统计消耗工时为3的任务数量
 - 第3条的name属性 @3
 - 第3条的value属性 @3
- 统计消耗工时为0的任务数量
 - 第0条的name属性 @0
 - 第0条的value属性 @12
- 统计消耗工时为2的任务数量
 - 第2条的name属性 @2
 - 第2条的value属性 @14

*/

global $tester;
$taskModule = $tester->loadModel('task');
$result     = $taskModule->getDataOfTasksPerConsumed();

r(count($result)) && p()               && e('3');    // 按消耗时间统计的数量
r($result)        && p('3:name,value') && e('3,3');  // 统计消耗工时为3的任务数量
r($result)        && p('0:name,value') && e('0,12'); // 统计消耗工时为0的任务数量
r($result)        && p('2:name,value') && e('2,14'); // 统计消耗工时为2的任务数量