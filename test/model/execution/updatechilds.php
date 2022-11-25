#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->updateChildsTest();
cid=1
pid=1

修改敏捷执行的父执行 >> 102,迭代2
修改敏捷执行的父执行 >> 133,阶段33
修改敏捷执行的父执行 >> 162,看板62
修改敏捷执行的父执行 >> 2
修改敏捷执行的父执行 >> 2
修改敏捷执行的父执行 >> 2

*/

$executionIDList   = array('101', '131', '161');
$sprintExecutionID = array('102', '103');
$stageExecutionID  = array('132', '133');
$kanbanExecutionID = array('162', '163');
$count             = array('0','1');

$sprintChilds = array('childs' => $sprintExecutionID);
$stageChilds  = array('childs' => $stageExecutionID);
$kanbanChilds = array('childs' => $kanbanExecutionID);

$execution = new executionTest();
r($execution->updateChildsTest($executionIDList[0], $count[0], $sprintChilds)) && p('0:id,name') && e('102,迭代2');  // 修改敏捷执行的父执行
r($execution->updateChildsTest($executionIDList[1], $count[0], $stageChilds))  && p('1:id,name') && e('133,阶段33'); // 修改敏捷执行的父执行
r($execution->updateChildsTest($executionIDList[2], $count[0], $kanbanChilds)) && p('0:id,name') && e('162,看板62'); // 修改敏捷执行的父执行
r($execution->updateChildsTest($executionIDList[0], $count[1], $sprintChilds)) && p()            && e('2');          // 修改敏捷执行的父执行
r($execution->updateChildsTest($executionIDList[1], $count[1], $stageChilds))  && p()            && e('2');          // 修改敏捷执行的父执行
r($execution->updateChildsTest($executionIDList[2], $count[1], $kanbanChilds)) && p()            && e('2');          // 修改敏捷执行的父执行

$db->restoreDB();