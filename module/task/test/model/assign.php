#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=taskModel->assign();
timeout=0
cid=1

- wait状态任务指派
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @old1
 - 第0条的new属性 @user92

- wait状态任务指派修改预计剩余
 - 第1条的field属性 @left
 - 第1条的old属性 @0
 - 第1条的new属性 @1

- doing状态任务指派
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @old2
 - 第0条的new属性 @user93

- done状态任务指派
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @old3
 - 第0条的new属性 @user94

- pause状态任务指派
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @old4
 - 第0条的new属性 @user95

- cancel状态任务指派
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @old5
 - 第0条的new属性 @user96

- closed状态任务指派
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @old6
 - 第0条的new属性 @user97

*/

function initData()
{
    $task = zdTable('task');
    $task->id->range('1-6');
    $task->execution->range('2,3,3,4');
    $task->name->prefix("任务")->range('1-6');
    $task->left->range('0');
    $task->assignedTo->prefix("old")->range('1-6');
    $task->status->range("wait,doing,done,pause,cancel,closed");

    $task->gen(6);

    $user = zdTable('user');
    $user->id->range('1-100');
    $user->account->range('1-100')->prefix('user');
    $user->password->range('f8e41d6c31824c01e5d67c61a8ae49e9,e10adc3949ba59abbe56e057f20f883e');
    $user->realname->range('1-100')->prefix("开发");
    $user->gen(50);
}

initData();
$taskIDlist = array('1','2','3','4','5','6');

$waitTask     = array('assignedTo' => 'user92','status' => 'wait');
$waitTaskLeft = array('assignedTo' => 'user91','status' => 'wait', 'left' => '1');
$doingTask    = array('assignedTo' => 'user93','status' => 'doing');
$doneTask     = array('assignedTo' => 'user94','status' => 'done');
$pauseTask    = array('assignedTo' => 'user95','status' => 'pause');
$cancelTask   = array('assignedTo' => 'user96','status' => 'cancel');
$closedTask   = array('assignedTo' => 'user97','status' => 'closed');

$task = new taskTest();
r($task->assignTest($taskIDlist[0],$waitTask))   && p('0:field,old,new') && e('assignedTo,old1,user92'); // wait状态任务指派
r($task->assignTest($taskIDlist[0],$waitTaskLeft)) && p('1:field,old,new') && e('left,0,1');           // wait状态任务指派修改预计剩余
r($task->assignTest($taskIDlist[1],$doingTask))  && p('0:field,old,new') && e('assignedTo,old2,user93'); // doing状态任务指派
r($task->assignTest($taskIDlist[2],$doneTask))   && p('0:field,old,new') && e('assignedTo,old3,user94'); // done状态任务指派
r($task->assignTest($taskIDlist[3],$pauseTask))  && p('0:field,old,new') && e('assignedTo,old4,user95'); // pause状态任务指派
r($task->assignTest($taskIDlist[4],$cancelTask)) && p('0:field,old,new') && e('assignedTo,old5,user96'); // cancel状态任务指派
r($task->assignTest($taskIDlist[5],$closedTask)) && p('0:field,old,new') && e('assignedTo,old6,user97'); // closed状态任务指派
