#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=测试taskModel->update();
timeout=0
cid=1

- 测试修改任务名称
 - 第1条的field属性 @name
 - 第1条的old属性 @任务1
 - 第1条的new属性 @修改后的任务名称
- 测试无修改 @没有数据更新
- 测试修改任务模块
 - 第0条的field属性 @module
 - 第0条的old属性 @24
 - 第0条的new属性 @25
- 测试修改任务指派人
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @old3
 - 第0条的new属性 @user94
- 测试修改任务类型
 - 第0条的field属性 @type
 - 第0条的old属性 @study
 - 第0条的new属性 @devel
- 测试修改任务状态
 - 第0条的field属性 @status
 - 第0条的old属性 @cancel
 - 第0条的new属性 @doing
- 测试修改任务优先级
 - 第0条的field属性 @pri
 - 第0条的old属性 @2
 - 第0条的new属性 @1
- 测试修改任务所属执行
 - 第0条的field属性 @execution
 - 第0条的old属性 @7
 - 第0条的new属性 @8
- 测试修改任务截止时间
 - 第0条的field属性 @deadline
 - 第0条的new属性 @2022-03-29
- 测试修改任务关闭原因第closedReason条的0属性 @ 『关闭原因』必须为空。
- 测试修改任务的父任务 @没有数据更新

*/

$execution = zdTable('project');
$execution->id->range('1-9');
$execution->name->prefix('执行')->range('1-9');
$execution->type->range('sprint{4},kanban{2},stage{3}');
$execution->parent->range('0');
$execution->path->range('1-9');
$execution->status->range('doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('(-3M)-(+M):1D')->type('timestamp')->format('YYYY-MM-DD');
$execution->end->range('(+5w)-(+2M):1D')->type('timestamp')->format('YYYY-MM-DD');
$execution->gen(9);

$task = zdTable('task');
$task->id->range('1-9');
$task->execution->range('1-9');
$task->story->range('1-9');
$task->name->prefix("任务")->range('1-9');
$task->left->range('0-8');
$task->estStarted->range('2022\-01\-01');
$task->assignedTo->prefix("old")->range('1-9');
$task->status->range("wait,doing,done,pause,cancel,closed");
$task->gen(9);

$deadline   = '2022-03-29';
$taskIDList = array('1','2','3','4','5','6','7','8','9');

$changename       = array('name' => '修改后的任务名称','deadline' => $deadline);
$changemodule     = array('module' => '25');
$changeassign     = array('assignedTo' => 'user94');
$changetype       = array('type' => 'devel');
$changestatus     = array('status' => 'doing');
$changepri        = array('pri' => '1');
$changeexecution  = array('execution' => '8');
$changedeadline   = array('deadline' => $deadline);
$closedReason     = array('closedReason' => '关闭原因');
$changeParent     = array('parent' => '1');

$task = new taskTest();
r($task->updateObject($taskIDList[0], $changename))       && p('1:field,old,new') && e('name,任务1,修改后的任务名称'); // 测试修改任务名称
r($task->updateObject($taskIDList[0], $changename))       && p()                  && e('没有数据更新');                // 测试无修改
r($task->updateObject($taskIDList[1], $changemodule))     && p('0:field,old,new') && e('module,24,25');                // 测试修改任务模块
r($task->updateObject($taskIDList[2], $changeassign))     && p('0:field,old,new') && e('assignedTo,old3,user94');      // 测试修改任务指派人
r($task->updateObject($taskIDList[3], $changetype))       && p('0:field,old,new') && e('type,study,devel');            // 测试修改任务类型
r($task->updateObject($taskIDList[4], $changestatus))     && p('0:field,old,new') && e('status,cancel,doing');         // 测试修改任务状态
r($task->updateObject($taskIDList[5], $changepri))        && p('0:field,old,new') && e('pri,2,1');                     // 测试修改任务优先级
r($task->updateObject($taskIDList[6], $changeexecution))  && p('0:field,old,new') && e('execution,7,8');               // 测试修改任务所属执行
r($task->updateObject($taskIDList[8], $changedeadline))   && p('0:field,new')     && e("deadline,2022-03-29");         // 测试修改任务截止时间
r($task->updateObject($taskIDList[0], $closedReason))     && p('closedReason:0')  && e(' 『关闭原因』必须为空。');     // 测试修改任务关闭原因
r($task->updateObject($taskIDList[8], $changeParent))     && p()                  && e('没有数据更新');                // 测试修改任务的父任务