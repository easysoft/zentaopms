#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

$task = zenData('task');
$task->id->range('1-9');
$task->name->setFields(array(
    array('field' => 'name1', 'range' => '父任务{3},子任务{3},普通任务{3}'),
    array('field' => 'name2', 'range' => '1-9')
));
$task->parent->range('0{3},1{2},2,0{3}');
$task->status->range('doing,closed,wait{2},doing{3},wait{2}');
$task->closedBy->range('[],admin,[]{7}');

$task->gen(9);
su('admin');

/**

title=taskModel->buildTaskTree();
timeout=0
cid=18864

- 测试空数据的情况 @0
- 测试数据中有父任务时，重构结构后的任务数量 @6
- 测试数据中有父任务时，重构结构后子任务的数据第children[4]条的name属性 @子任务4
- 测试数据中没有父任务时，重构结构后的任务数量 @6
- 测试数据中没有父任务时，重构结构后的子任务中父任务的名称第4条的parentName属性 @父任务1
- 测试数据中有父子任务时，重构结构后的子任务中父任务的名称第1条的name属性 @父任务1
- 测试数据中有父子任务时，重构结构后的任务数量 @2
- 测试数据中只有父任务时，重构结构后的父任务的名称第1条的name属性 @父任务1
- 测试数据中只有父任务时，重构结构后的任务数量 @3

*/

$taskTester = new taskTest();
$allTaskIdList       = range(1, 9);
$notParentTaskIdList = range(4, 9);
$hasParentTaskIdList = array(1, 3, 4);
$parentTaskIdList    = range(1, 3);

$emptyData           = $taskTester->buildTaskTreeTest(array());
$allTasks            = $taskTester->buildTaskTreeTest($allTaskIdList);
$notParentTasks      = $taskTester->buildTaskTreeTest($notParentTaskIdList);
$hasParentTasks      = $taskTester->buildTaskTreeTest($hasParentTaskIdList);
$parentTasks         = $taskTester->buildTaskTreeTest($parentTaskIdList);
$parentTask          = current($allTasks);
$allTasksCount       = count($allTasks);
$notParentTasksCount = count($notParentTasks);
$hasParentTasksCount = count($hasParentTasks);
$parentTasksCount    = count($parentTasks);

r($emptyData)           && p()                   && e('0');       // 测试空数据的情况
r($allTasksCount)       && p()                   && e('6');       // 测试数据中有父任务时，重构结构后的任务数量
r($parentTask)          && p('children[4]:name') && e('子任务4'); // 测试数据中有父任务时，重构结构后子任务的数据
r($notParentTasksCount) && p()                   && e('6');       // 测试数据中没有父任务时，重构结构后的任务数量
r($notParentTasks)      && p('4:parentName')     && e('父任务1'); // 测试数据中没有父任务时，重构结构后的子任务中父任务的名称
r($hasParentTasks)      && p('1:name')           && e('父任务1'); // 测试数据中有父子任务时，重构结构后的子任务中父任务的名称
r($hasParentTasksCount) && p()                   && e('2');       // 测试数据中有父子任务时，重构结构后的任务数量
r($parentTasks)         && p('1:name')           && e('父任务1'); // 测试数据中只有父任务时，重构结构后的父任务的名称
r($parentTasksCount)    && p()                   && e('3');       // 测试数据中只有父任务时，重构结构后的任务数量
