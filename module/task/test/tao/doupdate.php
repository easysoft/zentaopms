#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

/**

title=测试taskModel->doUpdate();
timeout=0
cid=18874

timeout=0
cid=18874

- 测试修改任务名称和截止日期
 - 属性name @修改后的任务名称
 - 属性deadline @2022-03-29
- 测试修改任务模块
 - 属性name @任务2
 - 属性module @25
- 测试修改任务指派人
 - 属性name @任务3
 - 属性assignedTo @user94
- 测试修改任务类型属性type @devel
- 测试修改任务状态属性status @doing
- 测试修改任务优先级属性pri @1
- 测试修改任务所属执行属性execution @8
- 测试修改任务截止时间属性deadline @2022-03-29
- 测试修改任务关闭原因第closedReason条的0属性 @『关闭原因』必须为空。
- 测试修改任务的父任务属性parent @1

*/

$execution = zenData('project');
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

$task = zenData('task');
$task->id->range('1-9');
$task->execution->range('1-9');
$task->name->prefix("任务")->range('1-9');
$task->left->range('0-8');
$task->estStarted->range('2022\-01\-01');
$task->assignedTo->prefix("old")->range('1-9');
$task->status->range("wait,doing,done,pause,cancel,closed");
$task->gen(9);

zenData('user')->gen(5);
su('admin');

$deadline   = '2022-03-29';
$taskIDList = array(1, 2, 3, 4, 5, 6, 7, 8, 9);

$changename       = array('name' => '修改后的任务名称','deadline' => $deadline);
$changemodule     = array('module' => '25');
$changeassign     = array('assignedTo' => 'user94');
$changetype       = array('type' => 'devel');
$changestatus     = array('status' => 'doing');
$changepri        = array('pri' => '1');
$changeexecution  = array('execution' => 8);
$changedeadline   = array('deadline' => $deadline);
$closedReason     = array('closedReason' => '关闭原因');
$changeParent     = array('parent' => '1');

$task = new taskTaoTest();
r($task->doUpdateTest($taskIDList[0], $changename))       && p('name,deadline')   && e('修改后的任务名称,2022-03-29'); // 测试修改任务名称和截止日期
r($task->doUpdateTest($taskIDList[1], $changemodule))     && p('name,module')     && e('任务2,25');                    // 测试修改任务模块
r($task->doUpdateTest($taskIDList[2], $changeassign))     && p('name,assignedTo') && e('任务3,user94');                // 测试修改任务指派人
r($task->doUpdateTest($taskIDList[3], $changetype))       && p('type')            && e('devel');                       // 测试修改任务类型
r($task->doUpdateTest($taskIDList[4], $changestatus))     && p('status')          && e('doing');                       // 测试修改任务状态
r($task->doUpdateTest($taskIDList[5], $changepri))        && p('pri')             && e('1');                           // 测试修改任务优先级
r($task->doUpdateTest($taskIDList[6], $changeexecution))  && p('execution')       && e('8');                           // 测试修改任务所属执行
r($task->doUpdateTest($taskIDList[8], $changedeadline))   && p('deadline')        && e("2022-03-29");                  // 测试修改任务截止时间
r($task->doUpdateTest($taskIDList[0], $closedReason))     && p('closedReason:0')  && e('『关闭原因』必须为空。');      // 测试修改任务关闭原因
r($task->doUpdateTest($taskIDList[8], $changeParent))     && p('parent')          && e('1');                           // 测试修改任务的父任务
