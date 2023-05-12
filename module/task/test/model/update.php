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
 - 第1条的old属性 @开发任务11
 - 第1条的new属性 @任务名修改

- 测试无修改 @没有数据更新

- 测试修改任务模块
 - 第0条的field属性 @module
 - 第0条的old属性 @24
 - 第0条的new属性 @25

- 测试修改任务指派人
 - 第1条的field属性 @assignedTo
 - 第1条的old属性 @
 - 第1条的new属性 @user94

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
 - 第0条的old属性 @107
 - 第0条的new属性 @101

- 测试修改任务截止时间
 - 第0条的field属性 @deadline
 - 第0条的new属性 @2022-03-29

- 测试修改任务关闭原因 @ 『关闭原因』必须为空。

*/

$deadline   = '2022-03-29';
$taskIDList = array('1','2','3','4','5','6','7','8','9');

$changename       = array('name' => '任务名修改','deadline' => $deadline);
$changemodule     = array('module' => '25');
$changeassign     = array('assignedTo' => 'user94');
$changetype       = array('type' => 'devel');
$changestatus     = array('status' => 'doing');
$changepri        = array('pri' => '1');
$changeexecution  = array('execution' => '101');
$changedeadline   = array('deadline' => $deadline);
$ckclosedReason   = array('closedReason' => '关闭原因');

$today       = helper::today();
$oldDeadline = $dao->select("date_sub('$today', interval 1 day) as date")->fetch()->date;

$task = new taskTest();
r($task->updateObject($taskIDList[0], $changename))       && p('1:field,old,new') && e('name,开发任务11,任务名修改');       // 测试修改任务名称
r($task->updateObject($taskIDList[0], $changename))       && p()                  && e('没有数据更新');                     // 测试无修改
r($task->updateObject($taskIDList[1], $changemodule))     && p('0:field,old,new') && e('module,24,25');                     // 测试修改任务模块
r($task->updateObject($taskIDList[2], $changeassign))     && p('1:field,old,new') && e('assignedTo,,user94');               // 测试修改任务指派人
r($task->updateObject($taskIDList[3], $changetype))       && p('0:field,old,new') && e('type,study,devel');                 // 测试修改任务类型
r($task->updateObject($taskIDList[4], $changestatus))     && p('0:field,old,new') && e('status,cancel,doing');              // 测试修改任务状态
r($task->updateObject($taskIDList[5], $changepri))        && p('0:field,old,new') && e('pri,2,1');                          // 测试修改任务优先级
r($task->updateObject($taskIDList[6], $changeexecution))  && p('0:field,old,new') && e('execution,107,101');                // 测试修改任务所属执行
r($task->updateObject($taskIDList[8], $changedeadline))   && p('0:field,new')     && e("deadline,2022-03-29");   // 测试修改任务截止时间
r($task->updateObject($taskIDList[0], $ckclosedReason))   && p('closedReason:0')  && e(' 『关闭原因』必须为空。');          // 测试修改任务关闭原因