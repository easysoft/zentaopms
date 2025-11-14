#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('user')->gen(10);
zenData('task')->loadYaml('task', true)->gen(30);

su('admin');

/**

title=taskModel->processExportTasks();
timeout=0
cid=18838

- 测试空数据 @0
- 测试处理任务数据返回ID为1的结果第1条的name属性 @开发任务12
- 测试处理任务数据返回ID为5的结果第5条的name属性 @开发任务16
- 测试处理任务数据返回ID为8的结果第8条的name属性 @开发任务19
- 测试处理任务数据返回ID为9的结果第9条的name属性 @开发任务20

*/

$taskTester = new taskTest();

$taskIdList = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
r($taskTester->processExportTasksTest(array()))     && p()         && e('0');          // 测试空数据
r($taskTester->processExportTasksTest($taskIdList)) && p('1:name') && e('开发任务12'); // 测试处理任务数据返回ID为1的结果
r($taskTester->processExportTasksTest($taskIdList)) && p('5:name') && e('开发任务16'); // 测试处理任务数据返回ID为5的结果
r($taskTester->processExportTasksTest($taskIdList)) && p('8:name') && e('开发任务19'); // 测试处理任务数据返回ID为8的结果
r($taskTester->processExportTasksTest($taskIdList)) && p('9:name') && e('开发任务20'); // 测试处理任务数据返回ID为9的结果
