#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('task')->loadYaml('task')->gen(30);

/**

title=taskModel->getDataOfTasksPerEstimate();
timeout=0
cid=18798

- 按预计工时统计的数量 @11
- 统计预计工时为1的任务数量
 - 第1条的name属性 @1
 - 第1条的value属性 @3
- 统计预计工时为2的任务数量
 - 第2条的name属性 @2
 - 第2条的value属性 @3
- 统计预计工时为3的任务数量
 - 第3条的name属性 @3
 - 第3条的value属性 @3
- 统计预计工时为4的任务数量
 - 第4条的name属性 @4
 - 第4条的value属性 @3

*/

global $tester;
$taskModule = $tester->loadModel('task');
$result     = $taskModule->getDataOfTasksPerEstimate();

r(count($result)) && p()               && e('11');  // 按预计工时统计的数量
r($result)        && p('1:name,value') && e('1,3'); // 统计预计工时为1的任务数量
r($result)        && p('2:name,value') && e('2,3'); // 统计预计工时为2的任务数量
r($result)        && p('3:name,value') && e('3,3'); // 统计预计工时为3的任务数量
r($result)        && p('4:name,value') && e('4,3'); // 统计预计工时为4的任务数量