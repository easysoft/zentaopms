#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

$execution = zdTable('project');
$execution->id->range('1-11');
$execution->name->setFields(array(
    array('field' => 'name1', 'range' => '项目{2},执行{3},迭代{2},阶段{2},看板{2}'),
    array('field' => 'name2', 'range' => '1-3'),
));
$execution->code->setFields(array(
    array('field' => 'name1', 'range' => '项目{2},执行{3},迭代{2},阶段{2},看板{2}'),
    array('field' => 'name2', 'range' => '1-3'),
));
$execution->type->range('project{2},sprint{5},waterfall{2},kanban{2}');
$execution->status->range('doing{11}');
$execution->parent->range('0');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);


/**

title=测试 taskModel->checkEstStartedAndDeadline();
cid=1
pid=1

测试获取执行4 任务开始日期 2000-01-02 结束日期 2040-01-02 是否可以保存 >> 任务开始日期应大于等于迭代的开始日期：2023-01-02。,任务结束日期应小于等于迭代的结束日期：2023-02-12。
测试获取执行4 任务开始日期 2000-01-02 结束日期 空 是否可以保存 >> 任务开始日期应大于等于迭代的开始日期：2023-01-02。,
测试获取执行4 任务开始日期 2000-01-02 结束日期 2023-01-15 是否可以保存 >> 任务开始日期应大于等于迭代的开始日期：2023-01-02。,
测试获取执行4 任务开始日期 空 结束日期 2040-01-02 是否可以保存 >> ,任务结束日期应小于等于迭代的结束日期：2023-02-12。
测试获取执行4 任务开始日期 空 结束日期 空 是否可以保存 >> 0
测试获取执行4 任务开始日期 空 结束日期 2023-01-15 是否可以保存 >> 0
测试获取执行4 任务开始日期 2023-01-15 结束日期 2040-01-02 是否可以保存 >> ,任务结束日期应小于等于迭代的结束日期：2023-02-12。
测试获取执行4 任务开始日期 2023-01-15 结束日期 空 是否可以保存 >> 0
测试获取执行4 任务开始日期 2023-01-15 结束日期 2023-01-15 是否可以保存 >> 0

*/

$executionID = '4';
$estStarted = array('2000-01-02', '', '2023-01-15');
$deadline   = array('2040-01-02', '', '2023-01-15');

$task = new taskTest();

global $tester;
$tester->loadModel('setting')->setItem('system.common.limitTaskDate', 1);

r($task->checkEstStartedAndDeadlineTest($executionID, $estStarted[0], $deadline[0])) && p('estStarted,deadline') && e('任务开始日期应大于等于迭代的开始日期：2023-01-02。,任务结束日期应小于等于迭代的结束日期：2023-02-12。'); // 测试获取执行4 任务开始日期 2000-01-02 结束日期 2040-01-02 是否可以保存
r($task->checkEstStartedAndDeadlineTest($executionID, $estStarted[0], $deadline[1])) && p('estStarted,deadline') && e('任务开始日期应大于等于迭代的开始日期：2023-01-02。,'); // 测试获取执行4 任务开始日期 2000-01-02 结束日期 空 是否可以保存
r($task->checkEstStartedAndDeadlineTest($executionID, $estStarted[0], $deadline[2])) && p('estStarted,deadline') && e('任务开始日期应大于等于迭代的开始日期：2023-01-02。,'); // 测试获取执行4 任务开始日期 2000-01-02 结束日期 2023-01-15 是否可以保存
r($task->checkEstStartedAndDeadlineTest($executionID, $estStarted[1], $deadline[0])) && p('estStarted,deadline') && e(',任务结束日期应小于等于迭代的结束日期：2023-02-12。'); // 测试获取执行4 任务开始日期 空 结束日期 2040-01-02 是否可以保存
r($task->checkEstStartedAndDeadlineTest($executionID, $estStarted[1], $deadline[1])) && p('estStarted,deadline') && e(0); // 测试获取执行4 任务开始日期 空 结束日期 空 是否可以保存
r($task->checkEstStartedAndDeadlineTest($executionID, $estStarted[1], $deadline[2])) && p('estStarted,deadline') && e(0); // 测试获取执行4 任务开始日期 空 结束日期 2023-01-15 是否可以保存
r($task->checkEstStartedAndDeadlineTest($executionID, $estStarted[2], $deadline[0])) && p('estStarted,deadline') && e(',任务结束日期应小于等于迭代的结束日期：2023-02-12。'); // 测试获取执行4 任务开始日期 2023-01-15 结束日期 2040-01-02 是否可以保存
r($task->checkEstStartedAndDeadlineTest($executionID, $estStarted[2], $deadline[1])) && p('estStarted,deadline') && e(0); // 测试获取执行4 任务开始日期 2023-01-15 结束日期 空 是否可以保存
r($task->checkEstStartedAndDeadlineTest($executionID, $estStarted[2], $deadline[2])) && p('estStarted,deadline') && e(0); // 测试获取执行4 任务开始日期 2023-01-15 结束日期 2023-01-15 是否可以保存

$tester->setting->setItem('system.common.limitTaskDate', 0);
