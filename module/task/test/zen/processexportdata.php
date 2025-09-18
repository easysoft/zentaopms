#!/usr/bin/env php
<?php

/**

title=测试 taskZen::processExportData();
timeout=0
cid=0

- 执行taskTest模块的processExportDataTest方法，参数是array  @rray()
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的name属性 @任务1
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的story属性 @需求1(#1)
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的fromBug属性 @#1 Bug1
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的name属性 @[父] 任务4
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的name属性 @[子] 任务5
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的name属性 @[多人] 任务6
- 执行taskTest模块的processExportDataTest方法，参数是array 
 - 第0条的mailto属性 @用户1
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的progress属性 @75%

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

// 2. zendata数据准备
$story = zenData('story');
$story->id->range('1');
$story->title->range('需求1');
$story->gen(1);

$bug = zenData('bug');
$bug->id->range('1');
$bug->title->range('Bug1');
$bug->gen(1);

$user = zenData('user');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->gen(3);

$project = zenData('project');
$project->id->range('1-2');
$project->name->range('项目1,执行1');
$project->type->range('project,execution');
$project->gen(2);

$module = zenData('module');
$module->id->range('1-2');
$module->name->range('模块1,模块2');
$module->type->range('task');
$module->gen(2);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建完整任务对象的辅助函数
function createTaskObject($id, $name, $options = array()) {
    $task = new stdClass();
    $task->id = $id;
    $task->name = $name;
    $task->project = isset($options['project']) ? $options['project'] : 1;
    $task->execution = isset($options['execution']) ? $options['execution'] : 1;
    $task->module = isset($options['module']) ? $options['module'] : 1;
    $task->story = isset($options['story']) ? $options['story'] : 0;
    $task->fromBug = isset($options['fromBug']) ? $options['fromBug'] : 0;
    $task->type = isset($options['type']) ? $options['type'] : 'devel';
    $task->pri = isset($options['pri']) ? $options['pri'] : 3;
    $task->status = isset($options['status']) ? $options['status'] : 'wait';
    $task->closedReason = isset($options['closedReason']) ? $options['closedReason'] : '';
    $task->mode = isset($options['mode']) ? $options['mode'] : '';
    $task->openedBy = isset($options['openedBy']) ? $options['openedBy'] : 'admin';
    $task->assignedTo = isset($options['assignedTo']) ? $options['assignedTo'] : 'admin';
    $task->finishedBy = isset($options['finishedBy']) ? $options['finishedBy'] : '';
    $task->canceledBy = isset($options['canceledBy']) ? $options['canceledBy'] : '';
    $task->closedBy = isset($options['closedBy']) ? $options['closedBy'] : '';
    $task->lastEditedBy = isset($options['lastEditedBy']) ? $options['lastEditedBy'] : '';
    $task->openedDate = isset($options['openedDate']) ? $options['openedDate'] : '2023-10-01 10:00:00';
    $task->assignedDate = isset($options['assignedDate']) ? $options['assignedDate'] : '2023-10-01 10:00:00';
    $task->finishedDate = isset($options['finishedDate']) ? $options['finishedDate'] : '0000-00-00 00:00:00';
    $task->canceledDate = isset($options['canceledDate']) ? $options['canceledDate'] : '0000-00-00 00:00:00';
    $task->closedDate = isset($options['closedDate']) ? $options['closedDate'] : '0000-00-00 00:00:00';
    $task->lastEditedDate = isset($options['lastEditedDate']) ? $options['lastEditedDate'] : '0000-00-00 00:00:00';
    $task->estimate = isset($options['estimate']) ? $options['estimate'] : 8;
    $task->consumed = isset($options['consumed']) ? $options['consumed'] : 0;
    $task->left = isset($options['left']) ? $options['left'] : 8;
    $task->mailto = isset($options['mailto']) ? $options['mailto'] : '';
    $task->parent = isset($options['parent']) ? $options['parent'] : 0;
    $task->isParent = isset($options['isParent']) ? $options['isParent'] : 0;
    $task->team = isset($options['team']) ? $options['team'] : '';
    $task->desc = isset($options['desc']) ? $options['desc'] : '';
    return $task;
}

// 步骤1：空任务数组处理
r($taskTest->processExportDataTest(array(), 1)) && p() && e(array());

// 步骤2：正常任务数据处理
$task1 = createTaskObject(1, '任务1');
r($taskTest->processExportDataTest(array($task1), 1)) && p('0:name') && e('任务1');

// 步骤3：包含关联需求的任务处理
$task2 = createTaskObject(2, '任务2', array('story' => 1, 'consumed' => 2, 'left' => 6));
r($taskTest->processExportDataTest(array($task2), 1)) && p('0:story') && e('需求1(#1)');

// 步骤4：包含关联Bug的任务处理
$task3 = createTaskObject(3, '任务3', array('fromBug' => 1, 'consumed' => 4, 'left' => 0));
r($taskTest->processExportDataTest(array($task3), 1)) && p('0:fromBug') && e('#1 Bug1');

// 步骤5：父任务名称处理
$task4 = createTaskObject(4, '任务4', array('isParent' => 1, 'consumed' => 8, 'left' => 0));
r($taskTest->processExportDataTest(array($task4), 1)) && p('0:name') && e('[父] 任务4');

// 步骤6：子任务名称处理
$task5 = createTaskObject(5, '任务5', array('parent' => 4, 'consumed' => 2, 'left' => 2));
r($taskTest->processExportDataTest(array($task5), 1)) && p('0:name') && e('[子] 任务5');

// 步骤7：多人任务名称处理
$task6 = createTaskObject(6, '任务6', array('team' => 'multi', 'consumed' => 5, 'left' => 3));
r($taskTest->processExportDataTest(array($task6), 1)) && p('0:name') && e('[多人] 任务6');

// 步骤8：邮件收件人格式化
$task7 = createTaskObject(7, '任务7', array('mailto' => 'user1,user2', 'consumed' => 1, 'left' => 7));
r($taskTest->processExportDataTest(array($task7), 1)) && p('0:mailto') && e('用户1,用户2');

// 步骤9：任务进度计算
$task8 = createTaskObject(8, '任务8', array('consumed' => 6, 'left' => 2));
r($taskTest->processExportDataTest(array($task8), 1)) && p('0:progress') && e('75%');