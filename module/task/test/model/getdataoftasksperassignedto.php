#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(10);
zenData('task')->loadYaml('task')->gen(30);

su('admin');

/**

title=taskModel->getDataOfTasksPerAssignedTo();
timeout=0
cid=18794

- 统计指派给的人数 @6
- 统计指派给为admin的任务数量
 - 第admin条的name属性 @admin
 - 第admin条的value属性 @6
- 统计指派给为user2的任务数量
 - 第user2条的name属性 @用户2
 - 第user2条的value属性 @3
- 统计指派给为user3的任务数量
 - 第user3条的name属性 @用户3
 - 第user3条的value属性 @3
- 统计指派给为user4的任务数量
 - 第user4条的name属性 @用户4
 - 第user4条的value属性 @3

*/

global $tester;
$taskModule = $tester->loadModel('task');

$result = $taskModule->getDataOfTasksPerAssignedTo();
r(count($result)) && p()                   && e('6');       // 统计指派给的人数
r($result)        && p('admin:name,value') && e('admin,6'); // 统计指派给为admin的任务数量
r($result)        && p('user2:name,value') && e('用户2,3'); // 统计指派给为user2的任务数量
r($result)        && p('user3:name,value') && e('用户3,3'); // 统计指派给为user3的任务数量
r($result)        && p('user4:name,value') && e('用户4,3'); // 统计指派给为user4的任务数量