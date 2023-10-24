#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->updateParentStatus();
cid=1
pid=1

更新任务ID为1 创建动态的任务的父任务 >> 1
更新任务ID为601 创建动态的任务的父任务 >> 1
更新任务ID为601 父ID为601 创建动态的任务的父任务 >> doing,更多任务1
更新任务ID为901 创建动态的任务的父任务 >> wait,子任务1
更新任务ID为901 父ID为601 创建动态的任务的父任务 >> wait,子任务1
更新任务ID为901 父ID为601 不创建动态的任务的父任务 >> wait,子任务1
更新任务ID为901 父ID为902 创建动态的任务的父任务 >> 1
更新任务ID为不存在的100001 父ID为601 创建动态的任务的父任务 >> 0
更新任务ID为不存在的100001 父ID为601 不创建动态的任务的父任务 >> 0
更新任务ID为不存在的100001 父ID为902 创建动态的任务的父任务 >> 1

*/

$taskIDList   = array('1', '601', '901', '100001');
$parentIDList = array('601', '902');

$task = new taskTest();
r($task->updateParentStatusTest($taskIDList[0]))                          && p('status,name') && e('1');               //更新任务ID为1 创建动态的任务的父任务
r($task->updateParentStatusTest($taskIDList[1]))                          && p('status,name') && e('1');               //更新任务ID为601 创建动态的任务的父任务
r($task->updateParentStatusTest($taskIDList[1], $parentIDList[0]))        && p('status,name') && e('doing,更多任务1'); //更新任务ID为601 父ID为601 创建动态的任务的父任务
r($task->updateParentStatusTest($taskIDList[2]))                          && p('status,name') && e('wait,子任务1');    //更新任务ID为901 创建动态的任务的父任务
r($task->updateParentStatusTest($taskIDList[2], $parentIDList[0]))        && p('status,name') && e('wait,子任务1');    //更新任务ID为901 父ID为601 创建动态的任务的父任务
r($task->updateParentStatusTest($taskIDList[2], $parentIDList[0], false)) && p('status,name') && e('wait,子任务1');    //更新任务ID为901 父ID为601 不创建动态的任务的父任务
r($task->updateParentStatusTest($taskIDList[2], $parentIDList[1]))        && p('status,name') && e('1');               //更新任务ID为901 父ID为902 创建动态的任务的父任务
r($task->updateParentStatusTest($taskIDList[3], $parentIDList[0]))        && p('status,name') && e('0');               //更新任务ID为不存在的100001 父ID为601 创建动态的任务的父任务
r($task->updateParentStatusTest($taskIDList[3], $parentIDList[0], false)) && p('status,name') && e('0');               //更新任务ID为不存在的100001 父ID为601 不创建动态的任务的父任务
r($task->updateParentStatusTest($taskIDList[3], $parentIDList[1]))        && p('status,name') && e('1');               //更新任务ID为不存在的100001 父ID为902 创建动态的任务的父任务
