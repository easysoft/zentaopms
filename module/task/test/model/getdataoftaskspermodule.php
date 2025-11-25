#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('task')->loadYaml('task', true)->gen(30);
zenData('module')->loadYaml('module', true)->gen(10);
zenData('branch')->loadYaml('branch', true)->gen(10);

/**

title=taskModel->getDataOfTasksPerModule();
timeout=0
cid=18802

- 按模块任务数统计的数量 @4
- 按模块任务数统计
 - 第2条的name属性 @/模块2
 - 第2条的value属性 @6
- 按模块任务数统计
 - 第3条的name属性 @/模块1/模块3
 - 第3条的value属性 @6
- 按模块任务数统计
 - 第0条的name属性 @/
 - 第0条的value属性 @9
- 按模块任务数统计
 - 第1条的name属性 @/模块1
 - 第1条的value属性 @9

*/

global $tester;
$taskModule = $tester->loadModel('task');
$result     = $taskModule->getDataOfTasksPerModule();

r(count($result)) && p()               && e('4');              // 按模块任务数统计的数量
r($result)        && p('2:name,value') && e('/模块2,6');       // 按模块任务数统计
r($result)        && p('3:name,value') && e('/模块1/模块3,6'); // 按模块任务数统计
r($result)        && p('0:name,value') && e('/,9');            // 按模块任务数统计
r($result)        && p('1:name,value') && e('/模块1,9');       // 按模块任务数统计