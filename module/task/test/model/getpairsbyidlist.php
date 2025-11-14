#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

// 手动插入测试数据
global $tester;
$dao = $tester->dao;

// 清理可能存在的测试数据
$dao->delete()->from(TABLE_TASK)->where('id')->le(20)->exec();

// 插入测试任务数据
$tasks = array(
    array('id' => 1, 'name' => '任务1', 'execution' => 1, 'deleted' => '0'),
    array('id' => 2, 'name' => '任务2', 'execution' => 1, 'deleted' => '0'), 
    array('id' => 3, 'name' => '任务3', 'execution' => 1, 'deleted' => '0'),
    array('id' => 9, 'name' => '任务9', 'execution' => 1, 'deleted' => '1'), // 已删除
    array('id' => 10, 'name' => '任务10', 'execution' => 1, 'deleted' => '1'), // 已删除
);

foreach($tasks as $task) {
    $dao->insert(TABLE_TASK)->data($task)->exec();
}

/**

title=taskModel->getPairsByIdList();
timeout=0
cid=18816

- 执行taskModel模块的getPairsByIdList方法，参数是array 属性1 @任务1
- 执行taskModel模块的getPairsByIdList方法，参数是array 属性2 @任务2
- 执行taskModel模块的getPairsByIdList方法，参数是array 属性3 @任务3
- 执行taskModel模块的getPairsByIdList方法，参数是array  @1
- 执行taskModel模块的getPairsByIdList方法，参数是array  @1

*/

$taskModel = $tester->loadModel('task');

r($taskModel->getPairsByIdList(array(1, 2, 3))) && p('1') && e('任务1');
r($taskModel->getPairsByIdList(array(1, 2, 3))) && p('2') && e('任务2');
r($taskModel->getPairsByIdList(array(1, 2, 3))) && p('3') && e('任务3');
r(isset($taskModel->getPairsByIdList(array())[0])) && p() && e('1');
r(count($taskModel->getPairsByIdList(array()))) && p() && e('1');