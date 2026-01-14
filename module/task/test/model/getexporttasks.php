#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('task')->loadYaml('task', true)->gen(30);

/**

title=taskModel->getExportTasks();
timeout=0
cid=18809

- 按照id倒序获取任务数据 @0
- 按照优先级正序获取任务数据 @0
- 按照条件获取任务数据第1条的name属性 @开发任务39
- 按照条件获取任务数据第1条的name属性 @开发任务39
- 按照条件获取任务数据第3条的name属性 @开发任务13

*/

$orderByList = array('id_desc', 'pri_asc');

$taskTester = new taskModelTest();

r($taskTester->getExportTasksTest($orderByList[0]))                          && p()         && e('0');          // 按照id倒序获取任务数据
r($taskTester->getExportTasksTest($orderByList[1]))                          && p()         && e('0');          // 按照优先级正序获取任务数据
r($taskTester->getExportTasksTest($orderByList[0], true))                    && p('1:name') && e('开发任务11'); // 按照条件获取任务数据
r($taskTester->getExportTasksTest($orderByList[0], false, true))             && p('1:name') && e('开发任务11'); // 按照条件获取任务数据
r($taskTester->getExportTasksTest($orderByList[0], false, true, 'selected')) && p('3:name') && e('开发任务13'); // 按照条件获取任务数据