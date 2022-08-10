#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->startTest();
cid=1
pid=1

敏捷执行开始 >> status,wait,doing
瀑布阶段开始 >> status,wait,doing
看板执行开始 >> status,wait,doing
重复执行开始 >> 此任务已被启动，不能重复启动！

*/

$executionIDList = array('101', '131', '161', '103');

$noRealBegan   = array('realBegan' => '');

$execution = new executionTest();
r($execution->startTest($executionIDList[0]))              && p('1:field,old,new') && e('status,wait,doing');              // 敏捷执行开始
r($execution->startTest($executionIDList[1]))              && p('1:field,old,new') && e('status,wait,doing');              // 瀑布阶段开始
r($execution->startTest($executionIDList[2]))              && p('1:field,old,new') && e('status,wait,doing');              // 看板执行开始
r($execution->startTest($executionIDList[0],$noRealBegan)) && p()                  && e('此任务已被启动，不能重复启动！'); // 重复执行开始
$db->restoreDB();