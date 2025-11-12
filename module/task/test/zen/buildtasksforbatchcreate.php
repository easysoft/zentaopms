#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTasksForBatchCreate();
timeout=0
cid=0

- 执行taskTest模块的buildTasksForBatchCreateTest方法，参数是$execution1, 0, $output1  @3
- 执行taskTest模块的buildTasksForBatchCreateTest方法，参数是$execution1, 0, $output1  @父级名称不能为空！
- 执行taskTest模块的buildTasksForBatchCreateTest方法，参数是$execution1, 0, $output1  @3
- 执行$result[0]->name @测试任务
- 执行project . ',' . $result[0]模块的execution方法  @1,1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

zenData('project')->gen(0);
zenData('story')->gen(0);
zenData('task')->gen(0);

su('admin');

$taskTest = new taskZenTest();

$execution1 = new stdClass();
$execution1->id = 1;
$execution1->project = 1;
$execution1->type = 'sprint';

$execution2 = new stdClass();
$execution2->id = 2;
$execution2->project = 1;
$execution2->type = 'kanban';

$output1 = array();
$output2 = array('laneID' => 10, 'columnID' => 20);

/* 测试1: 正常批量创建3个任务 */
$_POST = array();
$_POST['level'] = array(0, 0, 0);
$_POST['name'] = array('任务1', '任务2', '任务3');
$_POST['type'] = array('devel', 'test', 'devel');
$_POST['pri'] = array(1, 2, 3);
$_POST['estimate'] = array(5, 3, 8);
$_POST['assignedTo'] = array('user1', 'user2', 'user3');
$_POST['story'] = array(0, 0, 0);
$_POST['lane'] = array('', '', '');
$_POST['column'] = array('', '', '');
r(count($taskTest->buildTasksForBatchCreateTest($execution1, 0, $output1))) && p() && e('3');

/* 测试2: 父任务名称为空但子任务名称不为空 */
$_POST = array();
$_POST['level'] = array(0, 1);
$_POST['name'] = array('', '子任务1');
$_POST['type'] = array('devel', 'devel');
$_POST['pri'] = array(1, 1);
$_POST['estimate'] = array(0, 5);
$_POST['assignedTo'] = array('', 'user1');
$_POST['story'] = array(0, 0);
$_POST['lane'] = array('', '');
$_POST['column'] = array('', '');
r($taskTest->buildTasksForBatchCreateTest($execution1, 0, $output1)) && p() && e('父级名称不能为空！');

/* 测试3: 正常的父子任务创建,验证数量 */
$_POST = array();
$_POST['level'] = array(0, 1, 1);
$_POST['name'] = array('父任务', '子任务1', '子任务2');
$_POST['type'] = array('devel', 'devel', 'test');
$_POST['pri'] = array(1, 2, 2);
$_POST['estimate'] = array(0, 5, 3);
$_POST['assignedTo'] = array('', 'user1', 'user2');
$_POST['story'] = array(0, 0, 0);
$_POST['lane'] = array('', '', '');
$_POST['column'] = array('', '', '');
r(count($taskTest->buildTasksForBatchCreateTest($execution1, 0, $output1))) && p() && e('3');

/* 测试4: 验证第一个任务的基本属性 */
$_POST = array();
$_POST['level'] = array(0);
$_POST['name'] = array('测试任务');
$_POST['type'] = array('devel');
$_POST['pri'] = array(1);
$_POST['estimate'] = array(5);
$_POST['assignedTo'] = array('admin');
$_POST['story'] = array(0);
$_POST['lane'] = array('');
$_POST['column'] = array('');
$result = $taskTest->buildTasksForBatchCreateTest($execution1, 0, $output1);
r($result[0]->name) && p() && e('测试任务');

/* 测试5: 验证任务的project和execution属性 */
$_POST = array();
$_POST['level'] = array(0);
$_POST['name'] = array('任务A');
$_POST['type'] = array('devel');
$_POST['pri'] = array(1);
$_POST['estimate'] = array(10);
$_POST['assignedTo'] = array('user1');
$_POST['story'] = array(0);
$_POST['lane'] = array('');
$_POST['column'] = array('');
$result = $taskTest->buildTasksForBatchCreateTest($execution1, 0, $output1);
r($result[0]->project . ',' . $result[0]->execution) && p() && e('1,1');