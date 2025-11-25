#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

/**

title=taskModel->updateParentStatus();
timeout=0
cid=18858

- 执行task模块的updateParentStatusTest方法，参数是10 属性status @doing
- 执行task模块的updateParentStatusTest方法，参数是12 属性status @doing
- 执行task模块的updateParentStatusTest方法，参数是14 属性status @doing
- 执行task模块的updateParentStatusTest方法，参数是16 属性status @doing
- 执行task模块的updateParentStatusTest方法，参数是18 属性status @doing
- 执行task模块的updateParentStatusTest方法，参数是20 属性status @doing
- 执行task模块的updateParentStatusTest方法，参数是22 属性status @doing
- 执行task模块的updateParentStatusTest方法，参数是24 属性status @doing
- 执行task模块的updateParentStatusTest方法，参数是26 属性status @wait
- 执行task模块的updateParentStatusTest方法，参数是8 属性status @doing
- 执行task模块的updateParentStatusTest方法，参数是10 属性status @wait
- 执行task模块的updateParentStatusTest方法，参数是12 属性status @wait
- 执行task模块的updateParentStatusTest方法，参数是14 属性status @wait
- 执行task模块的updateParentStatusTest方法，参数是16 属性status @doing
- 执行task模块的updateParentStatusTest方法，参数是18 属性status @doing
- 执行task模块的updateParentStatusTest方法，参数是4 属性status @wait
- 执行task模块的updateParentStatusTest方法，参数是6 属性status @wait
- 执行task模块的updateParentStatusTest方法，参数是8 属性status @wait
- 执行task模块的updateParentStatusTest方法，参数是4
 - 属性status @done
 - 属性finishedBy @user1
 - 属性assignedTo @user2
- 执行task模块的updateParentStatusTest方法，参数是6
 - 属性status @done
 - 属性finishedBy @user1
 - 属性assignedTo @user2
- 执行task模块的updateParentStatusTest方法，参数是8
 - 属性status @done
 - 属性finishedBy @user1
 - 属性assignedTo @user2
- 执行task模块的updateParentStatusTest方法，参数是4
 - 属性status @wait
 - 属性closedBy @~~
 - 属性assignedTo @~~
 - 属性closedReason @~~
- 执行task模块的updateParentStatusTest方法，参数是6
 - 属性status @wait
 - 属性closedBy @~~
 - 属性assignedTo @~~
 - 属性closedReason @~~
- 执行task模块的updateParentStatusTest方法，参数是8
 - 属性status @wait
 - 属性finishedBy @~~
 - 属性canceledBy @~~

*/

zenData('user')->loadYaml('user')->gen(3);
zenData('project')->loadYaml('project')->gen(1);

su('user1');
$_SERVER['HTTP_HOST'] = 'pms.zentao.com';
$task = new taskTest();

/**
doing           + done   = doing
doing           + closed = doing
doing           + pause  = doing
doing           + cancel = doing
doing           + doing  = doing
doing           + wait   = doing
done            + wait   = doing
closeReasonDone + wait   = doing
*/

zenData('task')->loadYaml('taskdoing')->gen(26, true, false);

r($task->updateParentStatusTest(10)) && p('status') && e('doing');
r($task->updateParentStatusTest(12)) && p('status') && e('doing');
r($task->updateParentStatusTest(14)) && p('status') && e('doing');
r($task->updateParentStatusTest(16)) && p('status') && e('doing');
r($task->updateParentStatusTest(18)) && p('status') && e('doing');
r($task->updateParentStatusTest(20)) && p('status') && e('doing');
r($task->updateParentStatusTest(22)) && p('status') && e('doing');
r($task->updateParentStatusTest(24)) && p('status') && e('doing');
r($task->updateParentStatusTest(26)) && p('status') && e('wait');

/**
pause + done   = doing
pause + closed = wait
pause + pause  = wait
pause + cancel = wait
pause + doing  = doing
pause + wait   = doing
*/

zenData('task')->loadYaml('taskpause')->gen(18, true, false);

r($task->updateParentStatusTest(8))  && p('status') && e('doing');
r($task->updateParentStatusTest(10)) && p('status') && e('wait');
r($task->updateParentStatusTest(12)) && p('status') && e('wait');
r($task->updateParentStatusTest(14)) && p('status') && e('wait');
r($task->updateParentStatusTest(16)) && p('status') && e('doing');
r($task->updateParentStatusTest(18)) && p('status') && e('doing');

/**
wait + wait   = wait
wait + closed = wait
wait + cancel = wait
*/

zenData('task')->loadYaml('taskwait')->gen(9, true, false);

r($task->updateParentStatusTest(4)) && p('status') && e('wait');
r($task->updateParentStatusTest(6)) && p('status') && e('wait');
r($task->updateParentStatusTest(8)) && p('status') && e('wait');

/**
done + done   = done
done + closed = done
done + cancel = done
*/

zenData('task')->loadYaml('taskdone')->gen(9, true, false);

r($task->updateParentStatusTest(4)) && p('status,finishedBy,assignedTo') && e('done,user1,user2');
r($task->updateParentStatusTest(6)) && p('status,finishedBy,assignedTo') && e('done,user1,user2');
r($task->updateParentStatusTest(8)) && p('status,finishedBy,assignedTo') && e('done,user1,user2');

/**
closed + closed = wait
closed + cancel = wait
cancel + cancel = wait
*/

zenData('task')->loadYaml('taskclose')->gen(9, true, false);

r($task->updateParentStatusTest(4)) && p('status,closedBy,assignedTo,closedReason') && e('wait,~~,~~,~~');
r($task->updateParentStatusTest(6)) && p('status,closedBy,assignedTo,closedReason') && e('wait,~~,~~,~~');
r($task->updateParentStatusTest(8)) && p('status,finishedBy,canceledBy')            && e('wait,~~,~~,user2,assignedTo');


