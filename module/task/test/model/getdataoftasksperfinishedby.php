#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

$taskTable = zenData('task')->loadYaml('task');
$taskTable->finishedBy->range('admin,user1,user2,user3');
$taskTable->gen(30);

/**

title=taskModel->getDataOfTasksPerFinishedBy();
timeout=0
cid=18800

- 按由谁完成统计的数量 @4
- 完成者为admin的任务数量
 - 第admin条的name属性 @admin
 - 第admin条的value属性 @8
- 完成者为user1的任务数量
 - 第user1条的name属性 @用户1
 - 第user1条的value属性 @8
- 完成者为user2的任务数量
 - 第user2条的name属性 @用户2
 - 第user2条的value属性 @7
- 完成者为user3的任务数量
 - 第user3条的name属性 @用户3
 - 第user3条的value属性 @7

*/

global $tester;
$taskModule = $tester->loadModel('task');
$result     = $taskModule->getDataOfTasksPerFinishedBy();

r(count($result)) && p()                   && e('4');       // 按由谁完成统计的数量
r($result)        && p('admin:name,value') && e('admin,8'); // 完成者为admin的任务数量
r($result)        && p('user1:name,value') && e('用户1,8'); // 完成者为user1的任务数量
r($result)        && p('user2:name,value') && e('用户2,7'); // 完成者为user2的任务数量
r($result)        && p('user3:name,value') && e('用户3,7'); // 完成者为user3的任务数量