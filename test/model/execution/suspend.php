#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-7');
$execution->name->range('项目1,迭代1,迭代2,迭代3,迭代4,迭代5,迭代6');
$execution->type->range('project,sprint{2},waterfall{2},kanban{2}');
$execution->status->range('doing,wait');
$execution->gen(7);

su('admin');

/**

title=测试executionModel->suspendTest();
cid=1
pid=1

wait敏捷执行挂起  >> status,wait,suspended
doing敏捷执行挂起 >> status,doing,suspended
wait瀑布执行挂起  >> status,wait,suspended
doing瀑布执行挂起 >> status,doing,suspended
wait看板执行挂起  >> status,wait,suspended
doing看板执行挂起 >> status,doing,suspended
挂起后再次挂起    >> 0

*/

$executionIDList = array('2', '3', '4', '5', '6', '7');

$execution = new executionTest();
r($execution->suspendTest($executionIDList[0])) && p('0:field,old,new') && e('status,wait,suspended');  // wait敏捷执行挂起
r($execution->suspendTest($executionIDList[1])) && p('0:field,old,new') && e('status,doing,suspended'); // doing敏捷执行挂起
r($execution->suspendTest($executionIDList[2])) && p('0:field,old,new') && e('status,wait,suspended');  // wait瀑布执行挂起
r($execution->suspendTest($executionIDList[3])) && p('0:field,old,new') && e('status,doing,suspended'); // doing瀑布执行挂起
r($execution->suspendTest($executionIDList[4])) && p('0:field,old,new') && e('status,wait,suspended');  // wait看板执行挂起
r($execution->suspendTest($executionIDList[5])) && p('0:field,old,new') && e('status,doing,suspended'); // doing看板执行挂起
r($execution->suspendTest($executionIDList[0])) && p()                  && e('0');                      // 挂起后再次挂起
