#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('task')->loadYaml('task')->gen(30);

su('admin');

/**

title=taskModel->getDataOfTasksPerStatus();
timeout=0
cid=18804

- 统计状态为已完成的任务数量
 - 第done条的name属性 @已完成
 - 第done条的value属性 @4
- 统计状态为未开始的任务数量
 - 第wait条的name属性 @未开始
 - 第wait条的value属性 @13
- 统计状态为进行中的任务数量
 - 第doing条的name属性 @进行中
 - 第doing条的value属性 @7
- 统计状态为已取消的任务数量
 - 第cancel条的name属性 @已取消
 - 第cancel条的value属性 @3
- 统计状态为已关闭的任务数量
 - 第closed条的name属性 @已关闭
 - 第closed条的value属性 @3

*/

global $tester;
$taskModule = $tester->loadModel('task');

r($taskModule->getDataOfTasksPerStatus()) && p('done:name,value')   && e('已完成,4');  // 统计状态为已完成的任务数量
r($taskModule->getDataOfTasksPerStatus()) && p('wait:name,value')   && e('未开始,13'); // 统计状态为未开始的任务数量
r($taskModule->getDataOfTasksPerStatus()) && p('doing:name,value')  && e('进行中,7');  // 统计状态为进行中的任务数量
r($taskModule->getDataOfTasksPerStatus()) && p('cancel:name,value') && e('已取消,3');  // 统计状态为已取消的任务数量
r($taskModule->getDataOfTasksPerStatus()) && p('closed:name,value') && e('已关闭,3');  // 统计状态为已关闭的任务数量