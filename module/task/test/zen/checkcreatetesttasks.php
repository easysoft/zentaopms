#!/usr/bin/env php
<?php

/**

title=测试 taskZen::checkCreateTestTasks();
timeout=0
cid=18922

- 步骤1:传入空数组时返回错误 @1
- 步骤2:正常的测试任务数据验证通过 @1
- 步骤3:预估工时为负数时返回错误 @1
- 步骤4:截止日期小于预计开始日期时返回错误 @1
- 步骤5:缺少必填字段name时返回错误 @1
- 步骤6:多个任务中部分任务数据错误 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->type->range('project{5}, execution{5}');
$project->status->range('wait{5}, doing{5}');
$project->project->range('0{5}, 1-5');
$project->vision->range('rnd');
$project->begin->range('`2024-06-01`');
$project->end->range('`2024-12-31`');
$project->deleted->range('0');
$project->gen(10);

$story = zenData('story');
$story->id->range('1-5');
$story->title->range('需求{1-5}');
$story->product->range('1');
$story->status->range('active');
$story->stage->range('wait{2}, planned{3}');
$story->version->range('1');
$story->deleted->range('0');
$story->gen(5);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->role->range('admin{1}, dev{4}');
$user->deleted->range('0');
$user->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskZenTest = new taskZenTest();

// 5. 测试步骤(每个测试用例必须包含至少5个测试步骤)

// 步骤1:传入空数组时返回错误
$emptyTasks = array();
$result1 = $taskZenTest->checkCreateTestTasksTest($emptyTasks);
r(is_array($result1) && isset($result1[0]) && strpos($result1[0], '至少') !== false) && p() && e('1'); // 步骤1:传入空数组时返回错误

// 步骤2:正常的测试任务数据验证通过
$validTask = new stdClass();
$validTask->execution = 6;
$validTask->name = '测试任务1';
$validTask->type = 'test';
$validTask->estimate = 8;
$validTask->estStarted = '2024-06-05';
$validTask->deadline = '2024-06-15';
$validTask->assignedTo = 'user1';
$validTask->pri = 3;
$validTask->desc = '正常的测试任务';
$validTask->status = 'wait';
$validTasks = array($validTask);
r($taskZenTest->checkCreateTestTasksTest($validTasks)) && p() && e('1'); // 步骤2:正常的测试任务数据验证通过

// 步骤3:预估工时为负数时返回错误
$negativeEstimateTask = new stdClass();
$negativeEstimateTask->execution = 7;
$negativeEstimateTask->name = '测试任务2';
$negativeEstimateTask->type = 'test';
$negativeEstimateTask->estimate = -5;
$negativeEstimateTask->estStarted = '2024-06-05';
$negativeEstimateTask->deadline = '2024-06-15';
$negativeEstimateTask->assignedTo = 'user2';
$negativeEstimateTask->pri = 2;
$negativeEstimateTask->desc = '负数工时任务';
$negativeEstimateTask->status = 'wait';
$negativeEstimateTasks = array($negativeEstimateTask);
$result3 = $taskZenTest->checkCreateTestTasksTest($negativeEstimateTasks);
r(is_array($result3)) && p() && e('1'); // 步骤3:预估工时为负数时返回错误

// 步骤4:截止日期小于预计开始日期时返回错误
$invalidDateTask = new stdClass();
$invalidDateTask->execution = 8;
$invalidDateTask->name = '测试任务3';
$invalidDateTask->type = 'test';
$invalidDateTask->estimate = 10;
$invalidDateTask->estStarted = '2024-06-20';
$invalidDateTask->deadline = '2024-06-10';
$invalidDateTask->assignedTo = 'user3';
$invalidDateTask->pri = 1;
$invalidDateTask->desc = '日期错误任务';
$invalidDateTask->status = 'wait';
$invalidDateTasks = array($invalidDateTask);
$result4 = $taskZenTest->checkCreateTestTasksTest($invalidDateTasks);
r(is_array($result4)) && p() && e('1'); // 步骤4:截止日期小于预计开始日期时返回错误

// 步骤5:缺少必填字段name时返回错误
$missingFieldTask = new stdClass();
$missingFieldTask->execution = 9;
$missingFieldTask->type = 'test';
$missingFieldTask->name = '';
$missingFieldTask->estimate = 5;
$missingFieldTask->estStarted = '2024-06-05';
$missingFieldTask->deadline = '2024-06-15';
$missingFieldTask->assignedTo = 'user4';
$missingFieldTask->pri = 2;
$missingFieldTasks = array($missingFieldTask);
$result5 = $taskZenTest->checkCreateTestTasksTest($missingFieldTasks);
r(is_array($result5)) && p() && e('1'); // 步骤5:缺少必填字段name时返回错误

// 步骤6:多个任务中部分任务数据错误
$task1 = new stdClass();
$task1->execution = 6;
$task1->name = '正常任务';
$task1->type = 'test';
$task1->estimate = 8;
$task1->estStarted = '2024-06-05';
$task1->deadline = '2024-06-15';
$task1->assignedTo = 'user1';
$task1->pri = 3;
$task1->desc = '正常任务';
$task1->status = 'wait';

$task2 = new stdClass();
$task2->execution = 7;
$task2->name = '错误任务';
$task2->type = 'test';
$task2->estimate = -3;
$task2->estStarted = '2024-06-05';
$task2->deadline = '2024-06-15';
$task2->assignedTo = 'user2';
$task2->pri = 2;
$task2->desc = '工时为负数';
$task2->status = 'wait';

$multipleTasks = array($task1, $task2);
$result6 = $taskZenTest->checkCreateTestTasksTest($multipleTasks);
r(is_array($result6)) && p() && e('1'); // 步骤6:多个任务中部分任务数据错误