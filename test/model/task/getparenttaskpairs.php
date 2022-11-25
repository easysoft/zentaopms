#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getParentTaskPairs();
cid=1
pid=1

查找有父任务的执行下的父任务 普通任务和特定任务601 >> 更多任务1
查找有父任务的执行下的父任务 普通任务和特定任务602 >> 更多任务2
查找有父任务的执行下的父任务 普通任务和特定任务603 >> 更多任务3
查找有父任务的执行下的父任务 普通任务和特定任务11 >> 开发任务21
查找有父任务的执行下的父任务 普通任务和特定任务22 >> 开发任务32
查找有父任务的执行下的父任务 普通任务601 >> 更多任务1
查找有父任务的执行下的父任务 普通任务602 >> 更多任务2
查找有父任务的执行下的父任务 普通任务603 >> 更多任务3
查找没有父任务的执行下的父任务 普通任务12 >> 开发任务12
查找没有父任务的执行下的父任务 普通任务604 >> 更多任务4
查找不是执行的父任务 >> 0
查找不存在的执行下的父任务 >> 0

*/

$executionIDList = array('101','102','1','100001');
$append = '11,22';

$task = new taskTest();

r($task->getParentTaskPairsTest($executionIDList[0], $append)) && p('601') && e('更多任务1'); // 查找有父任务的执行下的父任务 普通任务和特定任务601
r($task->getParentTaskPairsTest($executionIDList[0], $append)) && p('602') && e('更多任务2'); // 查找有父任务的执行下的父任务 普通任务和特定任务602
r($task->getParentTaskPairsTest($executionIDList[0], $append)) && p('603') && e('更多任务3'); // 查找有父任务的执行下的父任务 普通任务和特定任务603
r($task->getParentTaskPairsTest($executionIDList[0], $append)) && p('11')  && e('开发任务21');// 查找有父任务的执行下的父任务 普通任务和特定任务11
r($task->getParentTaskPairsTest($executionIDList[0], $append)) && p('22')  && e('开发任务32');// 查找有父任务的执行下的父任务 普通任务和特定任务22
r($task->getParentTaskPairsTest($executionIDList[0])) && p('601') && e('更多任务1');          // 查找有父任务的执行下的父任务 普通任务601
r($task->getParentTaskPairsTest($executionIDList[0])) && p('602') && e('更多任务2');          // 查找有父任务的执行下的父任务 普通任务602
r($task->getParentTaskPairsTest($executionIDList[0])) && p('603') && e('更多任务3');          // 查找有父任务的执行下的父任务 普通任务603
r($task->getParentTaskPairsTest($executionIDList[1])) && p('2')   && e('开发任务12');         // 查找没有父任务的执行下的父任务 普通任务12
r($task->getParentTaskPairsTest($executionIDList[1])) && p('604')  && e('更多任务4');         // 查找没有父任务的执行下的父任务 普通任务604
r($task->getParentTaskPairsTest($executionIDList[2])) && p('name') && e('0');                 // 查找不是执行的父任务
r($task->getParentTaskPairsTest($executionIDList[3])) && p('name') && e('0');                 // 查找不存在的执行下的父任务