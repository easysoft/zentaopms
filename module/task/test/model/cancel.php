#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=taskModel->cancel();
timeout=0
cid=18770

- wait状态任务取消
 - 属性id @1
 - 属性name @任务1
 - 属性status @cancel
- doing状态任务取消
 - 属性id @2
 - 属性name @任务2
 - 属性status @cancel
- done状态任务取消
 - 属性id @3
 - 属性name @任务3
 - 属性status @cancel
- pause状态任务取消
 - 属性id @4
 - 属性name @任务4
 - 属性status @cancel
- cancel状态任务取消
 - 属性id @5
 - 属性name @任务5
 - 属性status @cancel
- closed状态任务取消
 - 属性id @6
 - 属性name @任务6
 - 属性status @cancel

*/

$task = zenData('task');
$task->id->range('1-6');
$task->execution->range('2');
$task->name->prefix("任务")->range('1-6');
$task->left->range('0-5');
$task->story->range('0{4},1{2}');
$task->estStarted->range('2022\-01\-01');
$task->assignedTo->prefix("old")->range('1-6');
$task->status->range("wait,doing,done,pause,cancel,closed");
$task->gen(6);

zenData('story')->gen(1);
zenData('product')->gen(1);
zenData('kanbanlane')->loadYaml('kanbanlane', true)->gen(10);
zenData('kanbancolumn')->loadYaml('kanbancolumn', true)->gen(18);
zenData('kanbancell')->loadYaml('kanbancell', true)->gen(18);

$taskIDlist = array(1, 2, 3, 4, 5, 6);

$task = new taskModelTest();
r($task->cancelTest($taskIDlist[0], array('status' => 'cancel', 'comment' => '取消备注1'))) && p('id,name,status') && e('1,任务1,cancel'); // wait状态任务取消
r($task->cancelTest($taskIDlist[1], array('status' => 'cancel', 'comment' => '取消备注2'))) && p('id,name,status') && e('2,任务2,cancel'); // doing状态任务取消
r($task->cancelTest($taskIDlist[2], array('status' => 'cancel', 'comment' => '取消备注3'))) && p('id,name,status') && e('3,任务3,cancel'); // done状态任务取消
r($task->cancelTest($taskIDlist[3], array('status' => 'cancel', 'comment' => '取消备注4'))) && p('id,name,status') && e('4,任务4,cancel'); // pause状态任务取消
r($task->cancelTest($taskIDlist[4], array('status' => 'cancel', 'comment' => '取消备注5'))) && p('id,name,status') && e('5,任务5,cancel'); // cancel状态任务取消
r($task->cancelTest($taskIDlist[5], array('status' => 'cancel', 'comment' => '取消备注6'))) && p('id,name,status') && e('6,任务6,cancel'); // closed状态任务取消
