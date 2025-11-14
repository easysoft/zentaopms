#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('task')->loadYaml('task')->gen(30);

/**

title=taskModel->getDataOfTasksPerPri();
timeout=0
cid=18803

- 统计优先级为1的任务数量
 - 第1条的name属性 @1
 - 第1条的value属性 @8
- 统计优先级为2的任务数量
 - 第2条的name属性 @2
 - 第2条的value属性 @8
- 统计优先级为3的任务数量
 - 第3条的name属性 @3
 - 第3条的value属性 @7
- 统计优先级为4的任务数量
 - 第4条的name属性 @4
 - 第4条的value属性 @7

*/

global $tester;
$taskModule = $tester->loadModel('task');

r($taskModule->getDataOfTasksPerPri()) && p('1:name,value') && e('1,8'); //统计优先级为1的任务数量
r($taskModule->getDataOfTasksPerPri()) && p('2:name,value') && e('2,8'); //统计优先级为2的任务数量
r($taskModule->getDataOfTasksPerPri()) && p('3:name,value') && e('3,7'); //统计优先级为3的任务数量
r($taskModule->getDataOfTasksPerPri()) && p('4:name,value') && e('4,7'); //统计优先级为4的任务数量