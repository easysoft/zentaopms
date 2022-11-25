#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->suspendTest();
cid=1
pid=1

wait敏捷执行挂起 >> status,wait,suspended
doing敏捷执行挂起 >> status,doing,suspended
wait瀑布执行挂起 >> status,wait,suspended
doing瀑布执行挂起 >> status,doing,suspended
wait看板执行挂起 >> status,wait,suspended
doing看板执行挂起 >> status,doing,suspended
挂起后再次挂起 >> 0

*/

$executionIDList = array('103', '104', '133', '134', '163', '164');

$execution = new executionTest();
r($execution->suspendTest($executionIDList[0])) && p('0:field,old,new') && e('status,wait,suspended');  // wait敏捷执行挂起
r($execution->suspendTest($executionIDList[1])) && p('0:field,old,new') && e('status,doing,suspended'); // doing敏捷执行挂起
r($execution->suspendTest($executionIDList[2])) && p('0:field,old,new') && e('status,wait,suspended');  // wait瀑布执行挂起
r($execution->suspendTest($executionIDList[3])) && p('0:field,old,new') && e('status,doing,suspended'); // doing瀑布执行挂起
r($execution->suspendTest($executionIDList[4])) && p('0:field,old,new') && e('status,wait,suspended');  // wait看板执行挂起
r($execution->suspendTest($executionIDList[5])) && p('0:field,old,new') && e('status,doing,suspended'); // doing看板执行挂起
r($execution->suspendTest($executionIDList[0])) && p()                  && e('0');                      // 挂起后再次挂起
$db->restoreDB();