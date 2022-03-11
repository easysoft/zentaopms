#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=测试taskModel->update();
cid=1
pid=1

测试修改任务名称 >> name,开发任务11,任务名修改
测试无修改 >> 没有数据更新
测试修改任务模块 >> module,24,25
测试修改任务指派人 >> assignedTo,,user94
测试修改任务类型 >> type,study,devel
测试修改任务状态 >> status,cancel,doing
测试修改任务优先级 >> pri,2,1
测试修改任务所属执行 >> execution,107,101
测试修改任务预计开始时间 >> estStarted,2022-03-11,2022-02-27
测试修改任务截止时间 >> deadline,2022-03-10,2022-03-11
测试修改任务关闭原因 >>  『关闭原因』必须为空。

*/

$estStarted = '2022-02-27';
$deadline   = '2022-03-11';
$taskIDList = array('1','2','3','4','5','6','7','8','9');

$changename       = array('name' => '任务名修改','estStarted' => $estStarted,'deadline' => $deadline);
$changemodule     = array('module' => '25');
$changeassign     = array('assignedTo' => 'user94');
$changetype       = array('type' => 'devel');
$changestatus     = array('status' => 'doing');
$changepri        = array('pri' => '1');
$changeexecution  = array('execution' => '101');
$changeestStarted = array('estStarted' => $estStarted);
$changedeadline   = array('deadline' => $deadline);
$ckclosedReason   = array('closedReason' => '关闭原因');

$task = new taskTest();
r($task->updateObject($taskIDList[0], $changename))       && p('2:field,old,new') && e('name,开发任务11,任务名修改');       // 测试修改任务名称
r($task->updateObject($taskIDList[0], $changename))       && p()                  && e('没有数据更新');                     // 测试无修改
r($task->updateObject($taskIDList[1], $changemodule))     && p('0:field,old,new') && e('module,24,25');                     // 测试修改任务模块
r($task->updateObject($taskIDList[2], $changeassign))     && p('1:field,old,new') && e('assignedTo,,user94');               // 测试修改任务指派人
r($task->updateObject($taskIDList[3], $changetype))       && p('0:field,old,new') && e('type,study,devel');                 // 测试修改任务类型
r($task->updateObject($taskIDList[4], $changestatus))     && p('0:field,old,new') && e('status,cancel,doing');              // 测试修改任务状态
r($task->updateObject($taskIDList[5], $changepri))        && p('0:field,old,new') && e('pri,2,1');                          // 测试修改任务优先级
r($task->updateObject($taskIDList[6], $changeexecution))  && p('0:field,old,new') && e('execution,107,101');                // 测试修改任务所属执行
r($task->updateObject($taskIDList[7], $changeestStarted)) && p('0:field,old,new') && e('estStarted,2022-03-11,2022-02-27'); // 测试修改任务预计开始时间
r($task->updateObject($taskIDList[8], $changedeadline))   && p('0:field,old,new') && e('deadline,2022-03-10,2022-03-11');   // 测试修改任务截止时间
r($task->updateObject($taskIDList[0], $ckclosedReason))   && p('closedReason:0')  && e(' 『关闭原因』必须为空。');          // 测试修改任务关闭原因

system("./ztest init");
