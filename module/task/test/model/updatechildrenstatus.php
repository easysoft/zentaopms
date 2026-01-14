#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=taskModel->updateChildrenStatus();
timeout=0
cid=18847

- 任务参数为空 @0
- 不是父任务 @0
- 任务不存在 @0
- 子任务的状态为wait，父任务的状态为pause，不更新子任务
 - 属性id @7
 - 属性status @wait
- 子任务的状态为done，父任务的状态为pause，不更新子任务
 - 属性id @7
 - 属性status @done
- 子任务的状态为cancel，父任务的状态为pause，不更新子任务
 - 属性id @7
 - 属性status @cancel
- 子任务的状态为closed，父任务的状态为pause，不更新子任务
 - 属性id @7
 - 属性status @closed
- 子任务的状态为doing，父任务的状态为pause，更新子任务
 - 属性id @7
 - 属性status @pause
 - 属性action @paused
 - 属性extra @autobyparent
- 子任务的状态为pause，父任务的状态为doing，不是自动暂停任务,不更新子任务
 - 属性id @7
 - 属性status @pause
- 子任务的状态为pause，父任务的状态为doing，自动暂停任务，更新子任务
 - 属性id @7
 - 属性status @doing
 - 属性action @restarted
 - 属性extra @autobyparent

*/

zenData('user')->loadYaml('user')->gen(3);
zenData('project')->loadYaml('project')->gen(3);
zenData('task')->loadYaml('task')->gen(9);

su('user1');
$_SERVER['HTTP_HOST'] = 'pms.zentao.com';
$task = new taskModelTest();

$task->objectModel->dao->update(TABLE_TASK)->set("path = concat(',', id, ',')")->exec();
$task->objectModel->dao->update(TABLE_TASK)->set("path = concat(',', parent, ',', id, ',')")->where('parent')->gt('0')->exec();
$task->objectModel->dao->update(TABLE_TASK)->set('isParent')->eq('1')->where('parent')->eq('-1')->exec();
$task->objectModel->dao->update(TABLE_TASK)->set('parent')->eq('0')->where('parent')->eq('-1')->exec();

r($task->updateChildrenStatusTest(0,  'pause')) && p() && e('0'); //任务参数为空
r($task->updateChildrenStatusTest(1,  'pause')) && p() && e('0'); //不是父任务
r($task->updateChildrenStatusTest(10, 'pause')) && p() && e('0'); //任务不存在

$task->objectModel->dao->update(TABLE_TASK)->set('status')->eq('wait')->where('id')->eq(7)->exec();
r($task->updateChildrenStatusTest(6, 'pause')) && p('id,status') && e('7,wait'); //子任务的状态为wait，父任务的状态为pause，不更新子任务

$task->objectModel->dao->update(TABLE_TASK)->set('status')->eq('done')->where('id')->eq(7)->exec();
r($task->updateChildrenStatusTest(6, 'pause')) && p('id,status') && e('7,done'); //子任务的状态为done，父任务的状态为pause，不更新子任务

$task->objectModel->dao->update(TABLE_TASK)->set('status')->eq('cancel')->where('id')->eq(7)->exec();
r($task->updateChildrenStatusTest(6, 'pause')) && p('id,status') && e('7,cancel'); //子任务的状态为cancel，父任务的状态为pause，不更新子任务

$task->objectModel->dao->update(TABLE_TASK)->set('status')->eq('closed')->where('id')->eq(7)->exec();
r($task->updateChildrenStatusTest(6, 'pause')) && p('id,status') && e('7,closed'); //子任务的状态为closed，父任务的状态为pause，不更新子任务

$task->objectModel->dao->update(TABLE_TASK)->set('status')->eq('doing')->where('id')->eq(7)->exec();
r($task->updateChildrenStatusTest(6, 'pause')) && p('id,status,action,extra') && e('7,pause,paused,autobyparent'); //子任务的状态为doing，父任务的状态为pause，更新子任务

$task->objectModel->dao->update(TABLE_ACTION)->set('extra')->eq('')->where('objectType')->eq('task')->andWhere('objectID')->eq(7)->exec();
r($task->updateChildrenStatusTest(6, 'doing')) && p('id,status') && e('7,pause'); //子任务的状态为pause，父任务的状态为doing，不是自动暂停任务,不更新子任务

$task->objectModel->dao->update(TABLE_ACTION)->set('extra')->eq('auto')->where('objectType')->eq('task')->andWhere('objectID')->eq(7)->andWhere('action')->eq('paused')->exec();
r($task->updateChildrenStatusTest(6, 'doing')) && p('id,status,action,extra') && e('7,doing,restarted,autobyparent'); //子任务的状态为pause，父任务的状态为doing，自动暂停任务，更新子任务