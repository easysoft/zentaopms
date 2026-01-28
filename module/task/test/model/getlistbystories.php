#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

global $tester;
$dao = $tester->dao;

// 清理可能存在的测试数据
$dao->delete()->from(TABLE_TASK)->where('id')->le(50)->exec();

// 插入测试任务数据
$tasks = array(
    array('id' => 1, 'project' => 1, 'execution' => 1, 'story' => 1, 'name' => '任务1', 'assignedTo' => 'admin', 'pri' => 1, 'status' => 'wait', 'estimate' => 5, 'consumed' => 2, 'left' => 3, 'closedReason' => '', 'deleted' => '0'),
    array('id' => 2, 'project' => 1, 'execution' => 1, 'story' => 1, 'name' => '任务2', 'assignedTo' => 'user1', 'pri' => 2, 'status' => 'doing', 'estimate' => 3, 'consumed' => 1, 'left' => 2, 'closedReason' => '', 'deleted' => '0'),
    array('id' => 3, 'project' => 1, 'execution' => 2, 'story' => 2, 'name' => '任务3', 'assignedTo' => 'user2', 'pri' => 1, 'status' => 'done', 'estimate' => 8, 'consumed' => 8, 'left' => 0, 'closedReason' => 'done', 'deleted' => '0'),
    array('id' => 4, 'project' => 2, 'execution' => 2, 'story' => 2, 'name' => '任务4', 'assignedTo' => 'admin', 'pri' => 3, 'status' => 'wait', 'estimate' => 4, 'consumed' => 0, 'left' => 4, 'closedReason' => '', 'deleted' => '0'),
    array('id' => 5, 'project' => 1, 'execution' => 3, 'story' => 3, 'name' => '任务5', 'assignedTo' => 'user1', 'pri' => 2, 'status' => 'doing', 'estimate' => 6, 'consumed' => 3, 'left' => 3, 'closedReason' => '', 'deleted' => '0'),
    array('id' => 6, 'project' => 2, 'execution' => 1, 'story' => 0, 'name' => '任务6', 'assignedTo' => '', 'pri' => 1, 'status' => 'cancel', 'estimate' => 2, 'consumed' => 0, 'left' => 0, 'closedReason' => 'cancel', 'deleted' => '0'),
    array('id' => 7, 'project' => 1, 'execution' => 2, 'story' => 2, 'name' => '任务7', 'assignedTo' => 'admin', 'pri' => 3, 'status' => 'done', 'estimate' => 5, 'consumed' => 5, 'left' => 0, 'closedReason' => 'done', 'deleted' => '1'), // 已删除
    array('id' => 8, 'project' => 1, 'execution' => 1, 'story' => 1, 'name' => '任务8', 'assignedTo' => 'user2', 'pri' => 2, 'status' => 'pause', 'estimate' => 3, 'consumed' => 1, 'left' => 2, 'closedReason' => '', 'deleted' => '0'),
);

foreach($tasks as $task) {
    $dao->insert(TABLE_TASK)->data($task)->exec();
}

/**

title=taskModel->getListByStories();
timeout=0
cid=18812

- 测试步骤1：根据需求ID 1,2,3获取任务（排除deleted=1的任务7） @6
- 测试步骤2：根据需求ID 1,2和执行ID 1获取任务 @3
- 测试步骤3：根据需求ID 1,2和项目ID 1获取任务 @4
- 测试步骤4：传入story=0的需求ID，会获取任务6 @1
- 测试步骤5：传入不存在的需求ID @0

*/

$taskTest = new taskModelTest();

r($taskTest->getListByStoriesTest(array(1, 2, 3))) && p() && e(6); // 测试步骤1：根据需求ID 1,2,3获取任务（排除deleted=1的任务7）
r($taskTest->getListByStoriesTest(array(1, 2), 1)) && p() && e(3); // 测试步骤2：根据需求ID 1,2和执行ID 1获取任务
r($taskTest->getListByStoriesTest(array(1, 2), 0, 1)) && p() && e(4); // 测试步骤3：根据需求ID 1,2和项目ID 1获取任务
r($taskTest->getListByStoriesTest(array(0))) && p() && e(1); // 测试步骤4：传入story=0的需求ID，会获取任务6
r($taskTest->getListByStoriesTest(array(999, 1000))) && p() && e(0); // 测试步骤5：传入不存在的需求ID